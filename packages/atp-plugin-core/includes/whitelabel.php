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

        // Save upload storage settings + OAuth client credentials
        update_option( 'atp_upload_storage', sanitize_text_field( $_POST['upload_storage'] ?? 'wordpress' ) );
        if ( isset( $_POST['drive_client_id'] ) || isset( $_POST['drive_client_secret'] ) ) {
            atp_drive_oauth_set( [
                'client_id'     => sanitize_text_field( $_POST['drive_client_id']     ?? '' ),
                'client_secret' => sanitize_text_field( $_POST['drive_client_secret'] ?? '' ),
            ] );
        }

        echo '<div class="notice notice-success is-dismissible"><p>Settings saved.</p></div>';
    }

    // ── Drive: Connect (kick off OAuth) ────────────────────────────────
    if ( isset( $_POST['atp_drive_connect'] ) && check_admin_referer( 'atp_wl_settings' ) ) {
        $authorize = atp_drive_authorize_url();
        if ( ! $authorize ) {
            echo '<div class="notice notice-error is-dismissible"><p>Save your OAuth Client ID and Client Secret first.</p></div>';
        } else {
            wp_redirect( $authorize );
            exit;
        }
    }

    // ── Drive: Disconnect ──────────────────────────────────────────────
    if ( isset( $_POST['atp_drive_disconnect'] ) && check_admin_referer( 'atp_wl_settings' ) ) {
        atp_drive_oauth_clear_tokens();
        update_option( 'atp_drive_config', [] );
        echo '<div class="notice notice-success is-dismissible"><p>Disconnected from Google Drive. Refresh token and folder selection cleared.</p></div>';
    }

    // ── Drive: OAuth callback (Google redirects here with ?code=&state=)
    if ( ( $_GET['atp_drive_oauth'] ?? '' ) === 'callback' ) {
        if ( ! empty( $_GET['error'] ) ) {
            echo '<div class="notice notice-error is-dismissible"><p>Drive authorization failed: '
                . esc_html( sanitize_text_field( $_GET['error'] ) ) . '</p></div>';
        } elseif ( ! empty( $_GET['code'] ) ) {
            $r = atp_drive_handle_oauth_callback(
                sanitize_text_field( $_GET['code'] ),
                sanitize_text_field( $_GET['state'] ?? '' )
            );
            if ( is_wp_error( $r ) ) {
                echo '<div class="notice notice-error is-dismissible"><p><strong>Connect failed:</strong> '
                    . esc_html( $r->get_error_message() ) . '</p></div>';
            } else {
                echo '<div class="notice notice-success is-dismissible"><p><strong>Google Drive connected.</strong> Now pick a destination folder below.</p></div>';
            }
        }
    }

    // ── Drive: Pick folder (POST from JS browser) ──────────────────────
    if ( isset( $_POST['atp_drive_pick_folder'] ) && check_admin_referer( 'atp_wl_settings' ) ) {
        $fid   = sanitize_text_field( $_POST['drive_pick_folder_id']   ?? '' );
        $fname = sanitize_text_field( $_POST['drive_pick_folder_name'] ?? '' );
        if ( $fid ) {
            update_option( 'atp_drive_config', [ 'folder_id' => $fid, 'folder_name' => $fname ] );
            echo '<div class="notice notice-success is-dismissible"><p><strong>Folder selected:</strong> '
                . esc_html( $fname ?: $fid ) . '</p></div>';
        }
    }

    // ── Drive: Test connection ─────────────────────────────────────────
    if ( isset( $_POST['atp_drive_test'] ) && check_admin_referer( 'atp_wl_settings' ) ) {
        if ( function_exists( 'atp_drive_test_connection' ) ) {
            $test_result = atp_drive_test_connection();
        } else {
            $test_result = new WP_Error( 'atp_drive_missing', 'Drive client not loaded.' );
        }
        if ( is_wp_error( $test_result ) ) {
            echo '<div class="notice notice-error is-dismissible"><p><strong>Drive test failed:</strong> '
                . esc_html( $test_result->get_error_message() ) . '</p></div>';
        } else {
            echo '<div class="notice notice-success is-dismissible"><p><strong>Drive test passed.</strong> '
                . esc_html( $test_result['message'] ) . ' Folder: <code>'
                . esc_html( $test_result['folder'] ) . '</code></p></div>';
        }
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
                </table>

                <h2 style="margin-top:24px">File Upload Storage</h2>
                <p class="description" style="max-width:780px">
                    Every intake submission's uploads are <strong>always saved to the WordPress Media Library</strong>
                    (organized by candidate). When Google Drive is connected and a folder is picked below, files are
                    <strong>also</strong> copied into Drive under <code>YYYY-MM-DD_Candidate-Name_Office-Slug</code>
                    subfolders. Drive is a secondary mirror, not a replacement.
                </p>
                <table class="form-table">
                    <tr>
                        <th><label for="upload_storage">Drive mirroring</label></th>
                        <td>
                            <?php $storage = get_option( 'atp_upload_storage', 'wordpress' ); ?>
                            <select name="upload_storage" id="upload_storage">
                                <option value="wordpress" <?php selected( $storage, 'wordpress' ); ?>>WordPress only</option>
                                <option value="google_drive" <?php selected( $storage, 'google_drive' ); ?>>WordPress + Google Drive</option>
                            </select>
                            <p class="description">When set to "WordPress + Google Drive", Drive must be connected and a folder picked.</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="drive_client_id">OAuth Client ID</label></th>
                        <td>
                            <?php $oauth = atp_drive_oauth_get(); ?>
                            <input type="text" name="drive_client_id" id="drive_client_id" value="<?php echo esc_attr( $oauth['client_id'] ); ?>" class="regular-text code" autocomplete="off">
                            <p class="description">From your Google Cloud project → APIs &amp; Services → Credentials → OAuth 2.0 Client IDs (Web application).</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="drive_client_secret">OAuth Client Secret</label></th>
                        <td>
                            <input type="password" name="drive_client_secret" id="drive_client_secret" value="<?php echo esc_attr( $oauth['client_secret'] ); ?>" class="regular-text code" autocomplete="off">
                            <p class="description">Stored in WordPress settings (admin-only).</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Authorized redirect URI</th>
                        <td>
                            <code style="background:#f6f7f7;padding:6px 10px;border-radius:3px;display:inline-block"><?php echo esc_html( atp_drive_redirect_uri() ); ?></code>
                            <p class="description">Add this exact URL to your OAuth client's "Authorized redirect URIs" in Google Cloud Console.</p>
                        </td>
                    </tr>
                </table>

                <h3 style="margin-top:18px">Connection</h3>
                <table class="form-table">
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php if ( atp_drive_is_connected() ) :
                                $cfg = get_option( 'atp_drive_config', [] );
                                ?>
                                <p>
                                    <span style="display:inline-block;width:10px;height:10px;background:#1a7f37;border-radius:50%;margin-right:6px"></span>
                                    <strong>Connected</strong>
                                    <?php if ( ! empty( $oauth['connected_email'] ) ) : ?>
                                        as <code><?php echo esc_html( $oauth['connected_email'] ); ?></code>
                                    <?php endif; ?>
                                </p>
                                <p>
                                    Picked folder:
                                    <?php if ( ! empty( $cfg['folder_id'] ) ) : ?>
                                        <strong><?php echo esc_html( $cfg['folder_name'] ?: $cfg['folder_id'] ); ?></strong>
                                        <code style="font-size:11px;color:#666">(<?php echo esc_html( $cfg['folder_id'] ); ?>)</code>
                                    <?php else : ?>
                                        <em>none yet — pick one below</em>
                                    <?php endif; ?>
                                </p>
                                <p>
                                    <button type="submit" name="atp_drive_disconnect" class="button button-secondary"
                                            onclick="return confirm('Disconnect Google Drive? The refresh token and folder selection will be cleared. Future intake uploads will go to WordPress only until you reconnect.');">Disconnect</button>
                                    <button type="submit" name="atp_drive_test" class="button button-secondary" style="margin-left:8px">Test Connection</button>
                                </p>
                            <?php elseif ( ! empty( $oauth['client_id'] ) && ! empty( $oauth['client_secret'] ) ) : ?>
                                <p>
                                    <span style="display:inline-block;width:10px;height:10px;background:#cc8400;border-radius:50%;margin-right:6px"></span>
                                    <strong>Not connected.</strong> Save your settings, then click Connect.
                                </p>
                                <p>
                                    <button type="submit" name="atp_drive_connect" class="button button-primary">Connect Google Drive</button>
                                </p>
                            <?php else : ?>
                                <p>
                                    <span style="display:inline-block;width:10px;height:10px;background:#999;border-radius:50%;margin-right:6px"></span>
                                    Enter your OAuth Client ID and Client Secret above, save, and a Connect button will appear.
                                </p>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <?php if ( atp_drive_is_connected() ) : ?>
                    <tr>
                        <th>Pick destination folder</th>
                        <td>
                            <button type="button" id="atp-drive-pick-btn" class="button">Browse my Drive…</button>
                            <input type="hidden" name="drive_pick_folder_id"   id="drive_pick_folder_id"   value="">
                            <input type="hidden" name="drive_pick_folder_name" id="drive_pick_folder_name" value="">
                            <p class="description">Click to browse your Drive folders and choose where intake submissions should be mirrored. The selected folder will receive one subfolder per submission.</p>

                            <div id="atp-drive-picker" style="display:none;margin-top:14px;border:1px solid #ddd;border-radius:6px;padding:14px;background:#fafafa;max-width:680px">
                                <div id="atp-drive-picker-crumbs" style="font-size:12px;color:#666;margin-bottom:10px"></div>
                                <ul id="atp-drive-picker-list" style="list-style:none;margin:0;padding:0;max-height:320px;overflow-y:auto;background:#fff;border:1px solid #e5e5e5;border-radius:4px"></ul>
                                <p style="margin-top:10px;display:flex;gap:8px;align-items:center">
                                    <button type="submit" name="atp_drive_pick_folder" id="atp-drive-pick-confirm" class="button button-primary" disabled>Pick this folder</button>
                                    <span id="atp-drive-pick-current" style="font-size:12px;color:#666"></span>
                                </p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </table>

                <p class="submit">
                    <button type="submit" name="atp_wl_save" class="button button-primary button-hero">Save Settings</button>
                </p>
            </form>

            <?php if ( atp_drive_is_connected() ) : ?>
            <script>
            (function(){
              const ajaxUrl = <?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>;
              const nonce   = <?php echo wp_json_encode( wp_create_nonce( 'atp_drive_browse' ) ); ?>;
              const picker  = document.getElementById('atp-drive-picker');
              const list    = document.getElementById('atp-drive-picker-list');
              const crumbs  = document.getElementById('atp-drive-picker-crumbs');
              const confirm = document.getElementById('atp-drive-pick-confirm');
              const current = document.getElementById('atp-drive-pick-current');
              const fidIn   = document.getElementById('drive_pick_folder_id');
              const fnIn    = document.getElementById('drive_pick_folder_name');
              let stack = [{ id: 'root', name: 'My Drive' }];

              document.getElementById('atp-drive-pick-btn').addEventListener('click', function(){
                picker.style.display = 'block';
                stack = [{ id: 'root', name: 'My Drive' }];
                load();
              });

              function load(){
                const here = stack[stack.length - 1];
                renderCrumbs();
                list.innerHTML = '<li style="padding:14px;color:#888">Loading…</li>';
                const fd = new FormData();
                fd.append('action', 'atp_drive_browse');
                fd.append('_ajax_nonce', nonce);
                fd.append('parent_id', here.id);
                fetch(ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
                  .then(r => r.json())
                  .then(json => {
                    if (!json || !json.success) {
                      list.innerHTML = '<li style="padding:14px;color:#cf222e">Error loading folders: ' + (json && json.data ? json.data : 'unknown') + '</li>';
                      return;
                    }
                    if (!json.data.length) {
                      list.innerHTML = '<li style="padding:14px;color:#888">No subfolders here.</li>';
                    } else {
                      list.innerHTML = '';
                      json.data.forEach(function(f){
                        const li = document.createElement('li');
                        li.style.cssText = 'display:flex;justify-content:space-between;align-items:center;padding:10px 12px;border-bottom:1px solid #eee';
                        const left = document.createElement('span');
                        left.innerHTML = '<span style="margin-right:8px">📁</span>' + escapeHtml(f.name);
                        const right = document.createElement('span');
                        const open = mkBtn('Open', function(){ stack.push({ id: f.id, name: f.name }); load(); });
                        const pick = mkBtn('Select', function(){
                          fidIn.value = f.id;
                          fnIn.value  = f.name;
                          confirm.disabled = false;
                          current.textContent = 'Selected: ' + f.name;
                          [...list.querySelectorAll('li')].forEach(x => x.style.background = '');
                          li.style.background = '#fff8e1';
                        }, 'button-primary');
                        right.appendChild(open);
                        right.appendChild(document.createTextNode(' '));
                        right.appendChild(pick);
                        li.appendChild(left);
                        li.appendChild(right);
                        list.appendChild(li);
                      });
                    }
                  })
                  .catch(err => {
                    list.innerHTML = '<li style="padding:14px;color:#cf222e">Network error: ' + escapeHtml(String(err)) + '</li>';
                  });
              }
              function renderCrumbs(){
                crumbs.innerHTML = '';
                stack.forEach(function(node, i){
                  if (i > 0) crumbs.appendChild(document.createTextNode(' / '));
                  if (i < stack.length - 1) {
                    const a = document.createElement('a');
                    a.href = '#'; a.textContent = node.name;
                    a.addEventListener('click', function(e){ e.preventDefault(); stack = stack.slice(0, i+1); load(); });
                    crumbs.appendChild(a);
                  } else {
                    const s = document.createElement('strong');
                    s.textContent = node.name;
                    crumbs.appendChild(s);
                  }
                });
                const here = stack[stack.length - 1];
                const pickHere = mkBtn('Select this folder', function(){
                  if (here.id === 'root') { alert('Pick a subfolder, not the root of your Drive.'); return; }
                  fidIn.value = here.id;
                  fnIn.value  = here.name;
                  confirm.disabled = false;
                  current.textContent = 'Selected: ' + here.name;
                });
                pickHere.style.marginLeft = '12px';
                if (here.id !== 'root') crumbs.appendChild(pickHere);
              }
              function mkBtn(label, onclick, extraClass){
                const b = document.createElement('button');
                b.type = 'button';
                b.className = 'button ' + (extraClass || '');
                b.textContent = label;
                b.addEventListener('click', onclick);
                return b;
              }
              function escapeHtml(s){ return String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }
            })();
            </script>
            <?php endif; ?>
        </div>
    </div>
    <?php
}
