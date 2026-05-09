<?php
/**
 * Plugin Name: ATP Marketing Site
 * Plugin URI:  https://americatrackingpolls.com
 * Description: Wraps ATP's static marketing pages (homepage mockup, brand guide, demo hub) so they can be installed as a WordPress plugin and viewed inside WordPress Playground or any WP install. The pages themselves remain static HTML/CSS/JS — this plugin just routes WP URLs to them and rewrites relative asset paths to plugin URLs.
 * Version:     1.0.0
 * Author:      Mirror Factory / America Tracking Polls
 * Text Domain: atp-marketing
 * Requires Plugins: vibe-ai
 *
 * Why this exists:
 *   The atp-website branch is a static marketing site. It works fine as a
 *   standalone repo + GitHub Pages. This plugin file is a lightweight
 *   adapter so the same files can also be loaded inside a WordPress
 *   Playground for demo/preview purposes — particularly to show the
 *   marketing site alongside the candidate-platform plugin in the same
 *   WP environment.
 *
 *   In production the marketing site is served as static files. This
 *   plugin is NOT required for ATP's live marketing site.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

const ATP_MKT_VERSION = '1.0.0';

/**
 * URL-key → static HTML filename in this plugin directory.
 */
function atp_mkt_pages() {
    return [
        'home'  => 'ATP-Homepage-Mockup.html',
        'brand' => 'brand-guide.html',
        'hub'   => 'index.html',
    ];
}

/* ─────────────────────────────────────────────────────────────────────────
   Rewrite rules: /marketing/, /marketing/brand/, /marketing/hub/
   ───────────────────────────────────────────────────────────────────────── */

add_action( 'init', 'atp_mkt_register_rewrites' );
function atp_mkt_register_rewrites() {
    add_rewrite_rule( '^marketing/?$',       'index.php?atp_mkt=home',  'top' );
    add_rewrite_rule( '^marketing/brand/?$', 'index.php?atp_mkt=brand', 'top' );
    add_rewrite_rule( '^marketing/hub/?$',   'index.php?atp_mkt=hub',   'top' );
}

add_filter( 'query_vars', function( $vars ) {
    $vars[] = 'atp_mkt';
    return $vars;
} );

register_activation_hook( __FILE__, function() {
    atp_mkt_register_rewrites();
    flush_rewrite_rules();
} );
register_deactivation_hook( __FILE__, function() {
    flush_rewrite_rules();
} );

/* ─────────────────────────────────────────────────────────────────────────
   Render the static page for the matching key.
   ───────────────────────────────────────────────────────────────────────── */

add_action( 'template_redirect', 'atp_mkt_serve' );
function atp_mkt_serve() {
    $key = get_query_var( 'atp_mkt' );
    if ( ! $key ) return;

    $pages = atp_mkt_pages();
    if ( ! isset( $pages[ $key ] ) ) return;

    $file = __DIR__ . '/' . $pages[ $key ];
    if ( ! is_readable( $file ) ) {
        wp_die( 'ATP Marketing: file not found — ' . esc_html( $pages[ $key ] ) );
    }

    $html = file_get_contents( $file );
    $base = rtrim( plugin_dir_url( __FILE__ ), '/' ) . '/';

    // Rewrite relative href= and src= URLs to plugin URLs so CSS/JS/images load.
    $html = preg_replace_callback(
        '/(href|src)\s*=\s*"([^"]+)"/i',
        function( $m ) use ( $base ) {
            $attr = $m[1];
            $url  = $m[2];
            // Skip absolute URLs, anchors, data:, mailto:, tel:, root-relative.
            if ( preg_match( '#^(https?:|//|#|data:|mailto:|tel:|/)#i', $url ) ) {
                return $m[0];
            }
            return $attr . '="' . $base . $url . '"';
        },
        $html
    );

    nocache_headers();
    header( 'Content-Type: text/html; charset=UTF-8' );
    echo $html;
    exit;
}

/* ─────────────────────────────────────────────────────────────────────────
   Admin menu: a small landing page with links to the three views.
   ───────────────────────────────────────────────────────────────────────── */

add_action( 'admin_menu', function() {
    add_menu_page(
        'ATP Marketing',
        'ATP Marketing',
        'manage_options',
        'atp-marketing',
        'atp_mkt_admin_page',
        'dashicons-megaphone',
        58
    );
} );

function atp_mkt_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) return;
    ?>
    <div class="wrap" style="max-width:780px">
        <h1>ATP Marketing Site</h1>
        <p>This plugin makes the ATP marketing pages browsable inside WordPress.
        It's primarily for previewing the site inside WordPress Playground —
        in production the marketing site is served as static HTML/CSS/JS, no
        plugin required.</p>

        <h2>Pages</h2>
        <ul style="list-style:disc;padding-left:24px;line-height:1.9">
            <li><a href="<?php echo esc_url( home_url( '/marketing/' ) ); ?>" target="_blank"><strong>Marketing Homepage</strong></a> — the main landing page (the one reviewed in the slide notes)</li>
            <li><a href="<?php echo esc_url( home_url( '/marketing/brand/' ) ); ?>" target="_blank"><strong>Brand Guide</strong></a> — colors, typography, logo usage</li>
            <li><a href="<?php echo esc_url( home_url( '/marketing/hub/' ) ); ?>" target="_blank"><strong>Demo Hub</strong></a> — original index landing</li>
        </ul>

        <h2>How this works</h2>
        <p>The plugin registers three rewrite rules that map <code>/marketing/*</code> URLs to the static HTML files in this plugin's directory. When a request comes in, the plugin reads the HTML, rewrites relative asset paths (<code>css/brand.css</code>, <code>js/brand-charts.js</code>, ATP logos) to plugin URLs, and serves the result. No database content, no shortcodes, no theme dependency.</p>

        <h2>Pretty permalinks required</h2>
        <p>If pages are returning 404s, go to <a href="<?php echo esc_url( admin_url( 'options-permalink.php' ) ); ?>">Settings → Permalinks</a> and click <em>Save Changes</em> to regenerate rewrite rules.</p>
    </div>
    <?php
}
