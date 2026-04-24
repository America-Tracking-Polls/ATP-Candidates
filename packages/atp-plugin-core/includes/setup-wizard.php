<?php
/**
 * ATP Setup Wizard — first-run assistant that installs required plugins
 * (Elementor, Yoast SEO) and guides the user through page import.
 *
 * @package ATPDemo
 * @since   1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ═════════════════════════════════════════════════════════════════════════════
   REQUIRED PLUGINS LIST
   ═════════════════════════════════════════════════════════════════════════ */

function atp_wizard_required_plugins() {
    return [
        'elementor' => [
            'name'   => 'Elementor',
            'slug'   => 'elementor',
            'file'   => 'elementor/elementor.php',
            'reason' => 'Canvas page template (no theme header/footer)',
        ],
        'yoast' => [
            'name'   => 'Yoast SEO',
            'slug'   => 'wordpress-seo',
            'file'   => 'wordpress-seo/wp-seo.php',
            'reason' => 'Focus keywords and meta descriptions',
        ],
    ];
}

/**
 * Get the status of a plugin: 'active', 'installed', or 'not_installed'.
 */
function atp_wizard_plugin_status( $file ) {
    if ( is_plugin_active( $file ) ) return 'active';
    $all = get_plugins();
    if ( isset( $all[ $file ] ) ) return 'installed';
    return 'not_installed';
}

/* ═════════════════════════════════════════════════════════════════════════════
   ADMIN NOTICE (show until setup is complete)
   ═════════════════════════════════════════════════════════════════════════ */

add_action( 'admin_notices', 'atp_wizard_admin_notice' );
function atp_wizard_admin_notice() {
    if ( get_option( 'atp_setup_complete' ) ) return;
    if ( ! current_user_can( 'manage_options' ) ) return;

    // Don't show on the wizard page itself.
    if ( isset( $_GET['page'] ) && $_GET['page'] === 'atp-setup-wizard' ) return;

    $url = admin_url( 'admin.php?page=atp-setup-wizard' );
    echo '<div class="notice notice-info is-dismissible" style="border-left-color:#B22234">'
        . '<p><strong style="color:#0B1C33">ATP Demo Plugin</strong> needs to be set up. '
        . '<a href="' . esc_url( $url ) . '" style="color:#B22234;font-weight:600">Run the Setup Wizard &rarr;</a></p></div>';
}

/* ═════════════════════════════════════════════════════════════════════════════
   ADMIN MENU
   ═════════════════════════════════════════════════════════════════════════ */

add_action( 'admin_menu', 'atp_wizard_menu' );
function atp_wizard_menu() {
    add_submenu_page(
        'atp-demo-shortcodes',
        'ATP Setup Wizard',
        'Setup Wizard',
        'manage_options',
        'atp-setup-wizard',
        'atp_wizard_render'
    );
}

// Handle restart wizard action
add_action( 'admin_init', 'atp_wizard_handle_restart' );
function atp_wizard_handle_restart() {
    if ( isset( $_GET['atp_restart_wizard'] ) && $_GET['atp_restart_wizard'] === '1'
        && current_user_can( 'manage_options' )
        && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'atp_restart_wizard' ) ) {
        delete_option( 'atp_setup_complete' );
        wp_redirect( admin_url( 'admin.php?page=atp-setup-wizard' ) );
        exit;
    }
}

/* ═════════════════════════════════════════════════════════════════════════════
   AJAX: Install Plugin
   ═════════════════════════════════════════════════════════════════════════ */

add_action( 'wp_ajax_atp_install_plugin', 'atp_wizard_ajax_install' );
function atp_wizard_ajax_install() {
    check_ajax_referer( 'atp_wizard_nonce', 'nonce' );

    if ( ! current_user_can( 'install_plugins' ) ) {
        wp_send_json_error( 'Permission denied.' );
    }

    $slug = sanitize_key( $_POST['plugin_slug'] ?? '' );
    if ( ! $slug ) wp_send_json_error( 'No plugin slug.' );

    require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

    $api = plugins_api( 'plugin_information', [ 'slug' => $slug, 'fields' => [ 'sections' => false ] ] );
    if ( is_wp_error( $api ) ) {
        wp_send_json_error( $api->get_error_message() );
    }

    $upgrader = new Plugin_Upgrader( new Automatic_Upgrader_Skin() );
    $result   = $upgrader->install( $api->download_link );

    if ( is_wp_error( $result ) ) {
        wp_send_json_error( $result->get_error_message() );
    }

    if ( ! $result ) {
        wp_send_json_error( 'Installation failed.' );
    }

    wp_send_json_success( 'Installed.' );
}

/* ═════════════════════════════════════════════════════════════════════════════
   AJAX: Activate Plugin
   ═════════════════════════════════════════════════════════════════════════ */

add_action( 'wp_ajax_atp_activate_plugin', 'atp_wizard_ajax_activate' );
function atp_wizard_ajax_activate() {
    check_ajax_referer( 'atp_wizard_nonce', 'nonce' );

    if ( ! current_user_can( 'activate_plugins' ) ) {
        wp_send_json_error( 'Permission denied.' );
    }

    $file = sanitize_text_field( $_POST['plugin_file'] ?? '' );
    if ( ! $file ) wp_send_json_error( 'No plugin file.' );

    $result = activate_plugin( $file );

    if ( is_wp_error( $result ) ) {
        wp_send_json_error( $result->get_error_message() );
    }

    wp_send_json_success( 'Activated.' );
}

/* ═════════════════════════════════════════════════════════════════════════════
   AJAX: Mark Setup Complete
   ═════════════════════════════════════════════════════════════════════════ */

add_action( 'wp_ajax_atp_complete_setup', 'atp_wizard_ajax_complete' );
function atp_wizard_ajax_complete() {
    check_ajax_referer( 'atp_wizard_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Permission denied.' );

    update_option( 'atp_setup_complete', '1' );
    wp_send_json_success( [ 'redirect' => admin_url( 'admin.php?page=atp-demo-shortcodes' ) ] );
}

/* ═════════════════════════════════════════════════════════════════════════════
   WIZARD PAGE RENDER
   ═════════════════════════════════════════════════════════════════════════ */

function atp_wizard_render() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    $plugins    = atp_wizard_required_plugins();
    $nonce      = wp_create_nonce( 'atp_wizard_nonce' );
    $page_sets  = function_exists( 'atp_importer_page_sets' ) ? atp_importer_page_sets() : [];
    ?>
    <style>
        .atp-wiz{max-width:800px;margin:30px auto;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif}
        .atp-wiz-header{background:#0B1C33;color:#fff;padding:30px 36px;border-radius:12px 12px 0 0;display:flex;align-items:center;gap:20px}
        .atp-wiz-header img{width:60px;height:auto}
        .atp-wiz-header h1{margin:0;font-size:24px;color:#fff}
        .atp-wiz-header p{margin:6px 0 0;color:#aaa;font-size:14px}
        .atp-wiz-body{background:#fff;border:1px solid #ddd;border-top:none;border-radius:0 0 12px 12px;padding:36px}
        .atp-wiz-step{margin-bottom:36px;display:none}
        .atp-wiz-step.active{display:block}
        .atp-wiz-step h2{font-size:20px;color:#0B1C33;margin:0 0 6px;display:flex;align-items:center;gap:10px}
        .atp-wiz-step h2 .step-num{background:#B22234;color:#fff;width:28px;height:28px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:14px;font-weight:700}
        .atp-wiz-step>p{color:#666;margin:0 0 20px;font-size:14px}
        .atp-wiz-plugin{display:flex;align-items:center;gap:16px;padding:16px 20px;border:1px solid #e5e5e5;border-radius:8px;margin-bottom:12px;background:#fafafa}
        .atp-wiz-plugin-info{flex:1}
        .atp-wiz-plugin-info strong{font-size:15px;color:#0B1C33}
        .atp-wiz-plugin-info small{display:block;color:#888;margin-top:2px;font-size:12px}
        .atp-wiz-status{font-size:12px;font-weight:600;padding:4px 10px;border-radius:4px;white-space:nowrap}
        .atp-wiz-status.active{background:#e8f5e9;color:#2e7d32}
        .atp-wiz-status.installed{background:#fff3e0;color:#e65100}
        .atp-wiz-status.not_installed{background:#fce4ec;color:#c62828}
        .atp-wiz-btn{background:#B22234;color:#fff;border:none;padding:8px 18px;border-radius:4px;cursor:pointer;font-size:13px;font-weight:600;transition:opacity .2s}
        .atp-wiz-btn:hover{opacity:.85}
        .atp-wiz-btn:disabled{opacity:.5;cursor:not-allowed}
        .atp-wiz-btn.secondary{background:#0B1C33}
        .atp-wiz-nav{display:flex;justify-content:space-between;align-items:center;margin-top:30px;padding-top:20px;border-top:1px solid #eee}
        .atp-wiz-pages{display:grid;grid-template-columns:1fr;gap:12px}
        .atp-wiz-page-card{display:flex;align-items:center;gap:16px;padding:14px 18px;border:1px solid #e5e5e5;border-radius:8px;background:#fafafa}
        .atp-wiz-page-card .dot{width:10px;height:10px;border-radius:50%;flex-shrink:0}
        .atp-wiz-page-card-info{flex:1}
        .atp-wiz-page-card-info strong{color:#0B1C33;font-size:14px}
        .atp-wiz-page-card-info small{display:block;color:#888;font-size:12px;margin-top:2px}
        .atp-wiz-done{text-align:center;padding:40px 0}
        .atp-wiz-done h2{font-size:28px;color:#2e7d32;margin-bottom:12px}
        .atp-wiz-done p{color:#666;font-size:15px;margin-bottom:24px}
        .atp-spinner{display:inline-block;width:14px;height:14px;border:2px solid rgba(255,255,255,.4);border-top-color:#fff;border-radius:50%;animation:atp-spin .6s linear infinite;vertical-align:middle;margin-left:6px}
        @keyframes atp-spin{to{transform:rotate(360deg)}}
    </style>

    <div class="atp-wiz">
        <div class="atp-wiz-header">
            <img src="<?php echo esc_url( ATP_DEMO_URL . 'assets/images/ATP-Logo-Red-White.png' ); ?>" alt="ATP">
            <div>
                <h1>ATP Setup Wizard</h1>
                <p>Let's get your site ready. This takes about 60 seconds.</p>
            </div>
        </div>
        <div class="atp-wiz-body">

            <!-- STEP 1: Required Plugins -->
            <div class="atp-wiz-step active" id="wiz-step-1">
                <h2><span class="step-num">1</span> Install Required Plugins</h2>
                <p>These plugins are needed for Canvas page templates and SEO optimization.</p>

                <?php foreach ( $plugins as $key => $plugin ) :
                    $status = atp_wizard_plugin_status( $plugin['file'] );
                ?>
                <div class="atp-wiz-plugin" id="plugin-<?php echo esc_attr( $key ); ?>" data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>" data-file="<?php echo esc_attr( $plugin['file'] ); ?>">
                    <div class="atp-wiz-plugin-info">
                        <strong><?php echo esc_html( $plugin['name'] ); ?></strong>
                        <small><?php echo esc_html( $plugin['reason'] ); ?></small>
                    </div>
                    <span class="atp-wiz-status <?php echo esc_attr( $status ); ?>" id="status-<?php echo esc_attr( $key ); ?>">
                        <?php
                        if ( $status === 'active' ) echo 'Active';
                        elseif ( $status === 'installed' ) echo 'Installed (inactive)';
                        else echo 'Not Installed';
                        ?>
                    </span>
                    <div id="actions-<?php echo esc_attr( $key ); ?>">
                        <?php if ( $status === 'not_installed' ) : ?>
                            <button class="atp-wiz-btn" onclick="atpWizInstall('<?php echo esc_js( $key ); ?>')">Install</button>
                        <?php elseif ( $status === 'installed' ) : ?>
                            <button class="atp-wiz-btn" onclick="atpWizActivate('<?php echo esc_js( $key ); ?>')">Activate</button>
                        <?php else : ?>
                            <span style="color:#2e7d32;font-weight:600;font-size:13px">&#10003; Ready</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>

                <div class="atp-wiz-nav">
                    <span></span>
                    <button class="atp-wiz-btn secondary" onclick="atpWizGoStep(2)">Next: Import Pages &rarr;</button>
                </div>
            </div>

            <!-- STEP 2: Import Pages -->
            <div class="atp-wiz-step" id="wiz-step-2">
                <h2><span class="step-num">2</span> Import Pages</h2>
                <p>Create your pages with all shortcodes, SEO meta, and Elementor Canvas template. You can also do this later from <strong>ATP Shortcodes &rarr; Import Pages</strong>.</p>

                <div class="atp-wiz-pages">
                    <?php foreach ( $page_sets as $ps_slug => $ps ) : ?>
                    <div class="atp-wiz-page-card" id="wiz-page-<?php echo esc_attr( $ps_slug ); ?>">
                        <div class="dot" style="background:<?php echo esc_attr( $ps['color'] ); ?>"></div>
                        <div class="atp-wiz-page-card-info">
                            <strong><?php echo esc_html( $ps['title'] ); ?></strong>
                            <small><?php echo count( $ps['shortcodes'] ); ?> shortcodes &middot; Focus: <?php echo esc_html( $ps['focus_kw'] ); ?></small>
                        </div>
                        <span class="atp-wiz-status" id="page-status-<?php echo esc_attr( $ps_slug ); ?>" style="display:none"></span>
                        <button class="atp-wiz-btn" id="page-btn-<?php echo esc_attr( $ps_slug ); ?>" onclick="atpWizImportPage('<?php echo esc_js( $ps_slug ); ?>')">Import</button>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="atp-wiz-nav">
                    <button class="atp-wiz-btn secondary" onclick="atpWizGoStep(1)">&larr; Back</button>
                    <button class="atp-wiz-btn" onclick="atpWizGoStep(3)">Finish Setup &rarr;</button>
                </div>
            </div>

            <!-- STEP 3: Done -->
            <div class="atp-wiz-step" id="wiz-step-3">
                <div class="atp-wiz-done">
                    <h2>&#10003; Setup Complete!</h2>
                    <p>Your ATP Demo plugin is ready. You can edit shortcodes, import more pages, or configure header/footer from the admin menu.</p>
                    <button class="atp-wiz-btn" onclick="atpWizComplete()" style="font-size:15px;padding:12px 30px">Go to ATP Dashboard</button>
                </div>
            </div>

            <?php if ( get_option( 'atp_setup_complete' ) ) : ?>
            <!-- Already completed — show restart option -->
            <div style="text-align:center;padding:20px 0 0;border-top:1px solid #eee;margin-top:20px">
                <p style="color:#888;font-size:13px;margin-bottom:10px">Setup was already completed. Want to run it again?</p>
                <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=atp-setup-wizard&atp_restart_wizard=1' ), 'atp_restart_wizard' ) ); ?>"
                   class="atp-wiz-btn secondary" style="text-decoration:none;display:inline-block">
                    Restart Setup Wizard
                </a>
            </div>
            <?php endif; ?>

        </div>
    </div>

    <script>
    var atpNonce = '<?php echo esc_js( $nonce ); ?>';
    var ajaxurl  = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';

    function atpWizGoStep(n) {
        document.querySelectorAll('.atp-wiz-step').forEach(s => s.classList.remove('active'));
        document.getElementById('wiz-step-' + n).classList.add('active');
    }

    function atpWizInstall(key) {
        var row = document.getElementById('plugin-' + key);
        var btn = row.querySelector('.atp-wiz-btn');
        var slug = row.dataset.slug;
        btn.disabled = true;
        btn.innerHTML = 'Installing<span class="atp-spinner"></span>';

        fetch(ajaxurl, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=atp_install_plugin&nonce=' + atpNonce + '&plugin_slug=' + slug
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('status-' + key).className = 'atp-wiz-status installed';
                document.getElementById('status-' + key).textContent = 'Installed';
                document.getElementById('actions-' + key).innerHTML = '<button class="atp-wiz-btn" onclick="atpWizActivate(\'' + key + '\')">Activate</button>';
            } else {
                btn.disabled = false;
                btn.textContent = 'Retry';
                alert('Install failed: ' + (data.data || 'Unknown error'));
            }
        })
        .catch(() => { btn.disabled = false; btn.textContent = 'Retry'; });
    }

    function atpWizActivate(key) {
        var row = document.getElementById('plugin-' + key);
        var btn = row.querySelector('.atp-wiz-btn');
        var file = row.dataset.file;
        btn.disabled = true;
        btn.innerHTML = 'Activating<span class="atp-spinner"></span>';

        fetch(ajaxurl, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=atp_activate_plugin&nonce=' + atpNonce + '&plugin_file=' + encodeURIComponent(file)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('status-' + key).className = 'atp-wiz-status active';
                document.getElementById('status-' + key).textContent = 'Active';
                document.getElementById('actions-' + key).innerHTML = '<span style="color:#2e7d32;font-weight:600;font-size:13px">&#10003; Ready</span>';
            } else {
                btn.disabled = false;
                btn.textContent = 'Retry';
                alert('Activate failed: ' + (data.data || 'Unknown error'));
            }
        })
        .catch(() => { btn.disabled = false; btn.textContent = 'Retry'; });
    }

    function atpWizImportPage(slug) {
        var btn = document.getElementById('page-btn-' + slug);
        var status = document.getElementById('page-status-' + slug);
        btn.disabled = true;
        btn.innerHTML = 'Importing<span class="atp-spinner"></span>';

        // Use the existing importer's form submission via AJAX.
        var formData = new FormData();
        formData.append('action', 'atp_wizard_import_page');
        formData.append('nonce', atpNonce);
        formData.append('page_slug', slug);

        fetch(ajaxurl, { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                btn.style.display = 'none';
                status.style.display = '';
                status.className = 'atp-wiz-status active';
                status.innerHTML = '&#10003; Imported';
            } else {
                btn.disabled = false;
                btn.textContent = 'Retry';
                status.style.display = '';
                status.className = 'atp-wiz-status not_installed';
                status.textContent = data.data || 'Failed';
            }
        })
        .catch(() => { btn.disabled = false; btn.textContent = 'Retry'; });
    }

    function atpWizComplete() {
        fetch(ajaxurl, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=atp_complete_setup&nonce=' + atpNonce
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.data.redirect) {
                window.location.href = data.data.redirect;
            }
        });
    }
    </script>
    <?php
}

/* ═════════════════════════════════════════════════════════════════════════════
   AJAX: Import page from wizard
   ═════════════════════════════════════════════════════════════════════════ */

add_action( 'wp_ajax_atp_wizard_import_page', 'atp_wizard_ajax_import_page' );
function atp_wizard_ajax_import_page() {
    check_ajax_referer( 'atp_wizard_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Permission denied.' );
    }

    $slug = sanitize_key( $_POST['page_slug'] ?? '' );

    if ( ! function_exists( 'atp_importer_page_sets' ) ) {
        wp_send_json_error( 'Importer not loaded.' );
    }

    $page_sets = atp_importer_page_sets();
    if ( ! isset( $page_sets[ $slug ] ) ) {
        wp_send_json_error( 'Unknown page set.' );
    }

    $set = $page_sets[ $slug ];

    // Duplicate check.
    if ( function_exists( 'atp_importer_page_exists' ) && atp_importer_page_exists( $set['title'] ) ) {
        wp_send_json_error( 'Page already exists.' );
    }

    // Build content.
    $content       = implode( "\n", $set['shortcodes'] );
    $use_elementor = defined( 'ELEMENTOR_VERSION' );
    $template      = $use_elementor ? 'elementor_canvas' : '';

    $page_id = wp_insert_post( [
        'post_title'    => $set['title'],
        'post_content'  => $content,
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_author'   => get_current_user_id(),
        'page_template' => $template,
    ], true );

    if ( is_wp_error( $page_id ) ) {
        wp_send_json_error( $page_id->get_error_message() );
    }

    // Elementor data.
    if ( $use_elementor && function_exists( 'atp_importer_set_elementor_data' ) ) {
        atp_importer_set_elementor_data( $page_id, $content );
    }

    // Hide title.
    update_post_meta( $page_id, '_atp_hide_title', '1' );

    // SEO.
    if ( function_exists( 'atp_importer_set_seo_meta' ) ) {
        atp_importer_set_seo_meta( $page_id, $set['focus_kw'], $set['meta_desc'] );
    }

    // Featured image.
    if ( function_exists( 'atp_importer_set_featured_image' ) ) {
        atp_importer_set_featured_image( $page_id, $set['focus_kw'] );
    }

    wp_send_json_success( [ 'page_id' => $page_id, 'url' => get_permalink( $page_id ) ] );
}
