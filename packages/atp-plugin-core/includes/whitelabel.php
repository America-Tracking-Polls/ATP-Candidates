<?php
/**
 * ATP White Label — custom branding for the WP admin, login page, and dashboard.
 *
 * Reads client branding from WP options (atp_whitelabel) which can be set
 * via the White Label settings page. Falls back to ATP defaults.
 *
 * @package ATP
 * @since   2.1.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* ─────────────────────────────────────────────────────────────────────────────
   Settings & Defaults
   ───────────────────────────────────────────────────────────────────────── */

function atp_wl_get() {
    return wp_parse_args( get_option( 'atp_whitelabel', [] ), [
        'client_name'     => 'John Stacy for Commissioner',
        'client_tagline'  => 'Rockwall County Commissioner, Precinct 4',
        'logo_url'        => '',
        'logo_width'      => '200px',
        'color_primary'   => '#0B1C33',
        'color_accent'    => '#E60000',
        'login_bg_image'  => '',
        'admin_footer'    => 'Powered by <strong>America Tracking Polls</strong> &bull; Mirror Factory',
        'dashboard_msg'   => 'Welcome to your campaign website dashboard. Use the shortcode editor to update content, or visit the front-end to preview your site.',
    ] );
}

/* ─────────────────────────────────────────────────────────────────────────────
   Custom Login Page
   ───────────────────────────────────────────────────────────────────────── */

add_action( 'login_enqueue_scripts', 'atp_wl_login_styles' );
function atp_wl_login_styles() {
    $wl = atp_wl_get();
    $primary = esc_attr( $wl['color_primary'] );
    $accent  = esc_attr( $wl['color_accent'] );
    $logo    = esc_url( $wl['logo_url'] );
    $logo_w  = esc_attr( $wl['logo_width'] );
    $bg      = esc_url( $wl['login_bg_image'] );
    ?>
    <style>
        body.login {
            background: <?php echo $primary; ?>;
            <?php if ( $bg ) : ?>
            background-image: url('<?php echo $bg; ?>');
            background-size: cover;
            background-position: center;
            <?php endif; ?>
        }
        body.login::before {
            content: '';
            position: fixed;
            inset: 0;
            background: <?php echo $primary; ?>;
            opacity: .85;
            z-index: 0;
        }
        #login {
            position: relative;
            z-index: 1;
        }
        .login h1 a {
            <?php if ( $logo ) : ?>
            background-image: url('<?php echo $logo; ?>') !important;
            background-size: contain !important;
            width: <?php echo $logo_w; ?> !important;
            height: 80px !important;
            <?php else : ?>
            font-size: 0;
            <?php endif; ?>
        }
        .login #backtoblog a,
        .login #nav a {
            color: rgba(255,255,255,.6) !important;
        }
        .login #backtoblog a:hover,
        .login #nav a:hover {
            color: #fff !important;
        }
        .login form {
            border-radius: 8px;
            border: none;
            box-shadow: 0 16px 48px rgba(0,0,0,.2);
        }
        .login .button-primary {
            background: <?php echo $accent; ?> !important;
            border-color: <?php echo $accent; ?> !important;
            box-shadow: none !important;
            text-shadow: none !important;
            border-radius: 4px !important;
        }
        .login .button-primary:hover {
            opacity: .9;
        }
        .login #login_error,
        .login .message,
        .login .success {
            border-left-color: <?php echo $accent; ?>;
        }
    </style>
    <?php
}

add_filter( 'login_headerurl', function() { return home_url(); } );
add_filter( 'login_headertext', function() {
    $wl = atp_wl_get();
    return $wl['client_name'];
} );

/* ─────────────────────────────────────────────────────────────────────────────
   Admin Bar & Footer Branding
   ───────────────────────────────────────────────────────────────────────── */

add_action( 'admin_head', 'atp_wl_admin_styles' );
function atp_wl_admin_styles() {
    $wl = atp_wl_get();
    $primary = esc_attr( $wl['color_primary'] );
    $accent  = esc_attr( $wl['color_accent'] );
    ?>
    <style>
        #wpadminbar { background: <?php echo $primary; ?> !important; }
        #wpadminbar .ab-item,
        #wpadminbar a.ab-item,
        #wpadminbar > #wp-toolbar span.ab-label,
        #wpadminbar > #wp-toolbar span.notif-count { color: rgba(255,255,255,.8) !important; }
        #wpadminbar .ab-item:hover,
        #wpadminbar a.ab-item:hover { color: #fff !important; }
        #adminmenu .wp-has-current-submenu .wp-submenu-head,
        #adminmenu a.wp-has-current-submenu { background: <?php echo $primary; ?> !important; }
        #adminmenu li.current a.menu-top,
        #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu { background: <?php echo $accent; ?> !important; }
    </style>
    <?php
}

add_filter( 'admin_footer_text', function() {
    $wl = atp_wl_get();
    return wp_kses_post( $wl['admin_footer'] );
} );

add_filter( 'update_footer', function() {
    return 'ATP v' . ATP_DEMO_VERSION;
}, 11 );

/* ─────────────────────────────────────────────────────────────────────────────
   Dashboard Widget
   ───────────────────────────────────────────────────────────────────────── */

add_action( 'wp_dashboard_setup', 'atp_wl_dashboard_widget' );
function atp_wl_dashboard_widget() {
    $wl = atp_wl_get();
    wp_add_dashboard_widget(
        'atp_wl_dashboard',
        esc_html( $wl['client_name'] ),
        'atp_wl_dashboard_render'
    );

    // Move to top
    global $wp_meta_boxes;
    $widget = $wp_meta_boxes['dashboard']['normal']['core']['atp_wl_dashboard'] ?? null;
    if ( $widget ) {
        unset( $wp_meta_boxes['dashboard']['normal']['core']['atp_wl_dashboard'] );
        $wp_meta_boxes['dashboard']['normal']['high']['atp_wl_dashboard'] = $widget;
    }
}

function atp_wl_dashboard_render() {
    $wl = atp_wl_get();
    $accent = esc_attr( $wl['color_accent'] );
    $logo   = esc_url( $wl['logo_url'] );
    ?>
    <div style="padding:8px 0">
        <?php if ( $logo ) : ?>
        <img src="<?php echo $logo; ?>" alt="Logo" style="max-width:160px;height:auto;margin-bottom:12px">
        <?php endif; ?>
        <p style="font-size:14px;line-height:1.6;color:#333;margin:0 0 16px"><?php echo esc_html( $wl['dashboard_msg'] ); ?></p>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=atp-demo-shortcodes' ) ); ?>"
               style="background:<?php echo $accent; ?>;color:#fff;padding:8px 16px;border-radius:3px;text-decoration:none;font-size:13px;font-weight:600">
                Edit Shortcodes
            </a>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=atp-import-pages' ) ); ?>"
               style="background:#333;color:#fff;padding:8px 16px;border-radius:3px;text-decoration:none;font-size:13px;font-weight:600">
                Import Pages
            </a>
            <a href="<?php echo esc_url( home_url() ); ?>" target="_blank"
               style="border:1px solid #ddd;color:#333;padding:8px 16px;border-radius:3px;text-decoration:none;font-size:13px;font-weight:600">
                View Site &rarr;
            </a>
        </div>
    </div>
    <?php
}

/* ─────────────────────────────────────────────────────────────────────────────
   White Label Settings Page
   ───────────────────────────────────────────────────────────────────────── */

add_action( 'admin_menu', 'atp_wl_admin_menu' );
function atp_wl_admin_menu() {
    add_submenu_page(
        'atp-demo-shortcodes',
        'White Label Settings',
        'White Label',
        'manage_options',
        'atp-whitelabel',
        'atp_wl_render_settings'
    );
}

function atp_wl_render_settings() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    // Save
    if ( isset( $_POST['atp_wl_save'] ) && check_admin_referer( 'atp_wl_settings' ) ) {
        $data = [
            'client_name'     => sanitize_text_field( $_POST['client_name'] ?? '' ),
            'client_tagline'  => sanitize_text_field( $_POST['client_tagline'] ?? '' ),
            'logo_url'        => esc_url_raw( $_POST['logo_url'] ?? '' ),
            'logo_width'      => sanitize_text_field( $_POST['logo_width'] ?? '200px' ),
            'color_primary'   => sanitize_hex_color( $_POST['color_primary'] ?? '#0B1C33' ),
            'color_accent'    => sanitize_hex_color( $_POST['color_accent'] ?? '#E60000' ),
            'login_bg_image'  => esc_url_raw( $_POST['login_bg_image'] ?? '' ),
            'admin_footer'    => wp_kses_post( $_POST['admin_footer'] ?? '' ),
            'dashboard_msg'   => sanitize_textarea_field( $_POST['dashboard_msg'] ?? '' ),
        ];
        update_option( 'atp_whitelabel', $data );
        echo '<div class="notice notice-success is-dismissible"><p>White label settings saved.</p></div>';
    }

    $wl = atp_wl_get();
    ?>
    <div class="wrap">
        <div style="display:flex;align-items:center;gap:14px;background:<?php echo esc_attr( $wl['color_primary'] ); ?>;padding:18px 28px;border-radius:6px 6px 0 0;margin:-1px -1px 0">
            <?php if ( $wl['logo_url'] ) : ?>
            <img src="<?php echo esc_url( $wl['logo_url'] ); ?>" alt="Logo" style="height:36px">
            <?php endif; ?>
            <div>
                <h1 style="margin:0;font-size:22px;color:#fff">White Label Settings</h1>
                <p style="margin:4px 0 0;color:rgba(255,255,255,.65);font-size:13px">
                    Customize the login page, admin dashboard, and branding for this client.
                </p>
            </div>
        </div>
        <div style="background:#fff;border:1px solid #ddd;border-top:none;border-radius:0 0 6px 6px;padding:28px 32px">
            <form method="post">
                <?php wp_nonce_field( 'atp_wl_settings' ); ?>
                <table class="form-table">
                    <tr>
                        <th><label for="client_name">Client / Campaign Name</label></th>
                        <td><input type="text" name="client_name" id="client_name" value="<?php echo esc_attr( $wl['client_name'] ); ?>" class="regular-text">
                        <p class="description">Appears on login page, dashboard widget, and admin bar.</p></td>
                    </tr>
                    <tr>
                        <th><label for="client_tagline">Tagline</label></th>
                        <td><input type="text" name="client_tagline" id="client_tagline" value="<?php echo esc_attr( $wl['client_tagline'] ); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="logo_url">Logo URL</label></th>
                        <td><input type="url" name="logo_url" id="logo_url" value="<?php echo esc_attr( $wl['logo_url'] ); ?>" class="regular-text">
                        <p class="description">Used on login page and dashboard. Paste any image URL.</p>
                        <?php if ( $wl['logo_url'] ) : ?>
                        <br><img src="<?php echo esc_url( $wl['logo_url'] ); ?>" style="max-height:48px;margin-top:6px;border:1px solid #ddd;padding:4px;border-radius:4px">
                        <?php endif; ?></td>
                    </tr>
                    <tr>
                        <th><label for="logo_width">Logo Width</label></th>
                        <td><input type="text" name="logo_width" id="logo_width" value="<?php echo esc_attr( $wl['logo_width'] ); ?>" class="small-text" placeholder="200px"></td>
                    </tr>
                    <tr>
                        <th><label for="color_primary">Primary Color</label></th>
                        <td><input type="color" name="color_primary" id="color_primary" value="<?php echo esc_attr( $wl['color_primary'] ); ?>" style="width:50px;height:36px;cursor:pointer">
                        <code><?php echo esc_html( $wl['color_primary'] ); ?></code>
                        <p class="description">Admin bar, login background, menu highlights.</p></td>
                    </tr>
                    <tr>
                        <th><label for="color_accent">Accent Color</label></th>
                        <td><input type="color" name="color_accent" id="color_accent" value="<?php echo esc_attr( $wl['color_accent'] ); ?>" style="width:50px;height:36px;cursor:pointer">
                        <code><?php echo esc_html( $wl['color_accent'] ); ?></code>
                        <p class="description">Login button, active menu items, CTA buttons.</p></td>
                    </tr>
                    <tr>
                        <th><label for="login_bg_image">Login Background Image</label></th>
                        <td><input type="url" name="login_bg_image" id="login_bg_image" value="<?php echo esc_attr( $wl['login_bg_image'] ); ?>" class="regular-text">
                        <p class="description">Optional background image for the login page. Primary color overlays at 85% opacity.</p></td>
                    </tr>
                    <tr>
                        <th><label for="admin_footer">Admin Footer Text</label></th>
                        <td><input type="text" name="admin_footer" id="admin_footer" value="<?php echo esc_attr( $wl['admin_footer'] ); ?>" class="regular-text">
                        <p class="description">Replaces "Thank you for creating with WordPress" in the footer. HTML allowed.</p></td>
                    </tr>
                    <tr>
                        <th><label for="dashboard_msg">Dashboard Welcome Message</label></th>
                        <td><textarea name="dashboard_msg" id="dashboard_msg" rows="3" class="large-text"><?php echo esc_textarea( $wl['dashboard_msg'] ); ?></textarea></td>
                    </tr>
                </table>
                <p class="submit">
                    <button type="submit" name="atp_wl_save" class="button button-primary button-hero">Save White Label Settings</button>
                </p>
            </form>
        </div>
    </div>
    <?php
}
