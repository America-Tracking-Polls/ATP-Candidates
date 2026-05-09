<?php
/**
 * ATP Site Config Loader
 *
 * Reads site-config.json from the plugin root and applies client-specific
 * settings (white label, pages, etc.) on plugin activation.
 *
 * @package ATP
 * @since   3.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function atp_get_site_config() {
    static $config = null;
    if ( $config !== null ) return $config;

    $file = ATP_DEMO_DIR . 'site-config.json';
    if ( ! file_exists( $file ) ) {
        $config = [];
        return $config;
    }

    $config = json_decode( file_get_contents( $file ), true );
    if ( ! is_array( $config ) ) $config = [];
    return $config;
}

// On activation: apply site-config to white label settings if not already set
register_activation_hook( ATP_DEMO_DIR . 'atp-demo-plugin.php', 'atp_apply_site_config' );
function atp_apply_site_config() {
    $config = atp_get_site_config();
    if ( empty( $config ) ) return;

    // Apply white label settings
    if ( ! empty( $config['whitelabel'] ) && ! get_option( 'atp_whitelabel' ) ) {
        $wl = $config['whitelabel'];
        $wl['client_name']    = $config['client_name'] ?? '';
        $wl['client_tagline'] = $config['client_tagline'] ?? '';
        update_option( 'atp_whitelabel', $wl );
    }

    // Set site title and tagline
    if ( ! empty( $config['client_name'] ) ) {
        update_option( 'blogname', $config['client_name'] );
    }
    if ( ! empty( $config['client_tagline'] ) ) {
        update_option( 'blogdescription', $config['client_tagline'] );
    }
}

// Load page overrides on init — if page-overrides/ directory exists,
// each file is named {shortcode_tag}.html and overrides that shortcode's default
add_action( 'init', 'atp_load_page_overrides', 20 );
function atp_load_page_overrides() {
    $dir = ATP_DEMO_DIR . 'page-overrides/';
    if ( ! is_dir( $dir ) ) return;

    $files = glob( $dir . '*.html' );
    foreach ( $files as $file ) {
        $tag = basename( $file, '.html' );
        $content = file_get_contents( $file );
        if ( $content !== false ) {
            $existing = get_option( 'atp_sc_' . $tag );
            if ( $existing === false ) {
                update_option( 'atp_sc_' . $tag, $content );
            }
        }
    }
}
