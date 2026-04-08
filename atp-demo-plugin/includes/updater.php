<?php
/**
 * GitHub-based auto-updater for the ATP Demo plugin.
 *
 * Checks the GitHub Releases API for a newer version and integrates
 * with the standard WordPress plugin-update flow so admins can update
 * from Dashboard > Updates just like any wp.org-hosted plugin.
 *
 * @package ATPDemo
 * @since   1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class ATP_Demo_GitHub_Updater
 *
 * Hooks into WordPress's plugin update pipeline to check GitHub releases,
 * surface available updates, supply download info, and rename the extracted
 * directory so it matches the installed plugin folder name.
 */
class ATP_Demo_GitHub_Updater {

	/**
	 * GitHub owner/repo path.
	 *
	 * @var string
	 */
	private $github_repo = 'mirror-factory/atp-demo';

	/**
	 * Subdirectory inside the repo that contains the plugin.
	 *
	 * @var string
	 */
	private $github_plugin_dir = 'atp-demo-plugin';

	/**
	 * Plugin slug used by WordPress (folder/file.php).
	 *
	 * @var string
	 */
	private $plugin_slug;

	/**
	 * Basename of the plugin directory (atp-demo-plugin).
	 *
	 * @var string
	 */
	private $plugin_dir_name;

	/**
	 * Currently installed version.
	 *
	 * @var string
	 */
	private $current_version;

	/**
	 * Transient key for caching the GitHub API response.
	 *
	 * @var string
	 */
	private $transient_key = 'atp_demo_github_release';

	/**
	 * How long (in seconds) to cache the API response.
	 * Default: 12 hours.
	 *
	 * @var int
	 */
	private $cache_duration = 43200;

	/**
	 * Cached release data fetched during this request.
	 *
	 * @var object|false|null  null = not fetched yet.
	 */
	private $release_data = null;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->plugin_slug    = plugin_basename( ATP_DEMO_DIR . 'atp-demo-plugin.php' );
		$this->plugin_dir_name = dirname( $this->plugin_slug ); // 'atp-demo-plugin'
		$this->current_version = defined( 'ATP_DEMO_VERSION' ) ? ATP_DEMO_VERSION : '0.0.0';
	}

	/**
	 * Register all hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_for_update' ) );
		add_filter( 'plugins_api', array( $this, 'plugin_info' ), 20, 3 );
		add_filter( 'upgrader_post_install', array( $this, 'post_install' ), 10, 3 );
	}

	/**
	 * Fetch the latest release data from GitHub (with transient caching).
	 *
	 * @return object|false  Decoded JSON object on success, false on failure.
	 */
	private function get_release_data() {
		if ( null !== $this->release_data ) {
			return $this->release_data;
		}

		$cached = get_transient( $this->transient_key );

		if ( false !== $cached ) {
			$this->release_data = $cached;
			return $this->release_data;
		}

		$url = sprintf(
			'https://api.github.com/repos/%s/releases/latest',
			$this->github_repo
		);

		$response = wp_remote_get(
			$url,
			array(
				'headers' => array(
					'Accept' => 'application/vnd.github.v3+json',
				),
				'timeout' => 10,
			)
		);

		if ( is_wp_error( $response ) ) {
			$this->release_data = false;
			return false;
		}

		$code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $code ) {
			$this->release_data = false;
			return false;
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body );

		if ( empty( $data ) || ! isset( $data->tag_name ) ) {
			$this->release_data = false;
			return false;
		}

		set_transient( $this->transient_key, $data, $this->cache_duration );
		$this->release_data = $data;

		return $this->release_data;
	}

	/**
	 * Normalise a version tag by stripping a leading "v" if present.
	 *
	 * GitHub tags may be "1.0.1" or "v1.0.1" -- handle both.
	 *
	 * @param string $tag Raw tag name.
	 * @return string
	 */
	private function sanitize_tag( $tag ) {
		return ltrim( $tag, 'vV' );
	}

	/**
	 * Build the download URL for the release zip.
	 *
	 * Prefers the first uploaded asset (a hand-crafted zip attached to the
	 * release). Falls back to the auto-generated source zipball.
	 *
	 * @param object $release GitHub release object.
	 * @return string
	 */
	private function get_download_url( $release ) {
		// Prefer an uploaded .zip asset (e.g. atp-demo-plugin.zip).
		if ( ! empty( $release->assets ) && is_array( $release->assets ) ) {
			foreach ( $release->assets as $asset ) {
				if ( isset( $asset->browser_download_url ) && '.zip' === substr( $asset->name, -4 ) ) {
					return $asset->browser_download_url;
				}
			}
		}

		// Fallback: auto-generated source zip.
		if ( ! empty( $release->zipball_url ) ) {
			return $release->zipball_url;
		}

		return '';
	}

	/**
	 * Inject update information into the update_plugins transient so
	 * WordPress displays the update notice.
	 *
	 * Hooked to: pre_set_site_transient_update_plugins
	 *
	 * @param object $transient The update_plugins transient object.
	 * @return object
	 */
	public function check_for_update( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$release = $this->get_release_data();

		if ( false === $release ) {
			return $transient;
		}

		$remote_version = $this->sanitize_tag( $release->tag_name );
		$download_url   = $this->get_download_url( $release );

		if ( empty( $download_url ) ) {
			return $transient;
		}

		if ( version_compare( $remote_version, $this->current_version, '>' ) ) {
			$plugin_data = new stdClass();

			$plugin_data->slug        = $this->plugin_dir_name;
			$plugin_data->plugin      = $this->plugin_slug;
			$plugin_data->new_version = $remote_version;
			$plugin_data->url         = sprintf( 'https://github.com/%s', $this->github_repo );
			$plugin_data->package     = $download_url;
			$plugin_data->icons       = array();
			$plugin_data->banners     = array();
			$plugin_data->tested      = '';
			$plugin_data->requires    = '5.0';
			$plugin_data->requires_php = '7.4';

			$transient->response[ $this->plugin_slug ] = $plugin_data;
		}

		return $transient;
	}

	/**
	 * Supply detailed plugin information for the "View details" thickbox
	 * on the Updates page and the Plugins page.
	 *
	 * Hooked to: plugins_api
	 *
	 * @param false|object|array $result The result object or array. Default false.
	 * @param string             $action The type of information being requested from the Plugin Installation API.
	 * @param object             $args   Plugin API arguments.
	 * @return false|object
	 */
	public function plugin_info( $result, $action, $args ) {
		if ( 'plugin_information' !== $action ) {
			return $result;
		}

		if ( ! isset( $args->slug ) || $args->slug !== $this->plugin_dir_name ) {
			return $result;
		}

		$release = $this->get_release_data();

		if ( false === $release ) {
			return $result;
		}

		$remote_version = $this->sanitize_tag( $release->tag_name );
		$download_url   = $this->get_download_url( $release );

		$info                 = new stdClass();
		$info->name           = 'ATP Demo Shortcodes';
		$info->slug           = $this->plugin_dir_name;
		$info->version        = $remote_version;
		$info->author         = '<a href="https://roiamplified.com">Mirror Factory / ROI Amplified</a>';
		$info->homepage       = sprintf( 'https://github.com/%s', $this->github_repo );
		$info->requires       = '5.0';
		$info->requires_php   = '7.4';
		$info->downloaded     = 0;
		$info->last_updated   = ! empty( $release->published_at ) ? $release->published_at : '';
		$info->download_link  = $download_url;

		// Use the release body (Markdown) as the description.
		$info->sections = array(
			'description'  => ! empty( $release->body ) ? wp_kses_post( nl2br( make_clickable( esc_html( $release->body ) ) ) ) : 'No changelog provided.',
			'changelog'    => ! empty( $release->body ) ? wp_kses_post( nl2br( make_clickable( esc_html( $release->body ) ) ) ) : 'No changelog provided.',
		);

		return $info;
	}

	/**
	 * After the updater extracts the zip, rename the extracted directory
	 * so it matches the expected plugin folder name.
	 *
	 * GitHub's auto-generated zips extract to a directory like
	 * "atp-demo-1.0.1/" (repo-tag) which breaks WordPress plugin detection.
	 * We rename it to "atp-demo-plugin/" to match the installed folder.
	 *
	 * Hooked to: upgrader_post_install
	 *
	 * @param bool  $response   Installation response.
	 * @param array $hook_extra Extra arguments passed to hooked filters.
	 * @param array $result     Installation result data.
	 * @return array|WP_Error
	 */
	public function post_install( $response, $hook_extra, $result ) {
		// Only act on this plugin's updates.
		if ( ! isset( $hook_extra['plugin'] ) || $hook_extra['plugin'] !== $this->plugin_slug ) {
			return $result;
		}

		global $wp_filesystem;

		$install_dir = $result['destination'];
		$proper_dir  = trailingslashit( WP_PLUGIN_DIR ) . $this->plugin_dir_name;

		// If the extracted directory is not already named correctly, rename it.
		if ( $install_dir !== $proper_dir ) {
			$wp_filesystem->move( $install_dir, $proper_dir, true );
			$result['destination']      = $proper_dir;
			$result['destination_name'] = $this->plugin_dir_name;
			$result['remote_destination'] = $proper_dir;
		}

		// Re-activate the plugin if it was active before the update.
		$active_plugins = get_option( 'active_plugins', array() );

		if ( in_array( $this->plugin_slug, $active_plugins, true ) ) {
			activate_plugin( $this->plugin_slug );
		}

		return $result;
	}

	/**
	 * Delete the cached release transient.
	 *
	 * Useful for forcing a fresh check, e.g. after an update completes.
	 *
	 * @return void
	 */
	public function clear_cache() {
		delete_transient( $this->transient_key );
		$this->release_data = null;
	}
}

/*
 * Boot the updater.
 *
 * Runs on `init` so all WordPress APIs (transients, HTTP, etc.) are available.
 */
add_action( 'init', function () {
	$updater = new ATP_Demo_GitHub_Updater();
	$updater->init();
} );
