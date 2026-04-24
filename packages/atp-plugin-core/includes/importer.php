<?php
/**
 * ATP Page Importer — one-click page creation with SEO meta and featured image.
 *
 * Adds a submenu under "ATP Shortcodes" that lets admins import pre-built
 * page sets (Homepage, Brand Guide, Candidate Intake Form) as real WordPress
 * pages complete with Yoast SEO metadata and a featured image.
 *
 * @package ATPDemo
 * @since   1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ─────────────────────────────────────────────────────────────────────────────
   Page-set definitions
   ───────────────────────────────────────────────────────────────────────── */

/**
 * Return the full list of importable page sets.
 *
 * Each key is the page set slug. Values include the page title, shortcodes,
 * SEO focus keyword, meta description, accent colour (for UI), and a short
 * human-readable description.
 *
 * @return array
 */
function atp_importer_page_sets() {
    return [
        'candidate-intake' => [
            'title'       => 'Candidate Intake Form',
            'desc'        => 'The ATP candidate onboarding form. Saves submissions as custom-post-type entries and sends email notifications.',
            'color'       => '#2E2D5A',
            'shortcodes'  => [
                '[atp_intake]',
            ],
            'focus_kw'    => 'candidate intake form',
            'meta_desc'   => 'Complete the candidate intake form to get started with America Tracking Polls campaign services.',
        ],
        'candidate-page' => [
            'title'       => 'Candidate Landing Page',
            'desc'        => 'Full campaign website with 12 sections. Uses John Stacy example content as showcase.',
            'color'       => '#0B1C33',
            'shortcodes'  => [
                '[atp_cand_styles]',
                '[atp_cand_nav]',
                '[atp_cand_hero]',
                '[atp_cand_stats]',
                '[atp_cand_about]',
                '[atp_cand_messages]',
                '[atp_cand_issues]',
                '[atp_cand_endorsements]',
                '[atp_cand_video]',
                '[atp_cand_volunteer]',
                '[atp_cand_survey]',
                '[atp_cand_donate]',
                '[atp_cand_social]',
                '[atp_cand_footer]',
            ],
            'focus_kw'    => 'campaign website candidate',
            'meta_desc'   => 'Official campaign website — learn about the candidate, key issues, endorsements, and how to support the campaign.',
        ],
        'candidate-issues' => [
            'title'       => 'Issues & Answers',
            'desc'        => 'Detailed policy positions page with expandable issue cards.',
            'color'       => '#0B1C33',
            'shortcodes'  => [
                '[atp_cand_styles]',
                '[atp_cand_nav]',
                '[atp_cand_issues_page]',
                '[atp_cand_footer]',
            ],
            'focus_kw'    => 'john stacy issues rockwall county',
            'meta_desc'   => 'Where Commissioner John Stacy stands on growth, roads, taxes, and responsible development in Rockwall County.',
        ],
        'candidate-privacy' => [
            'title'       => 'Privacy Policy',
            'desc'        => 'Campaign privacy policy with SMS/TCPA compliance.',
            'color'       => '#555555',
            'shortcodes'  => [
                '[atp_cand_styles]',
                '[atp_cand_nav]',
                '[atp_cand_privacy]',
                '[atp_cand_footer]',
            ],
            'focus_kw'    => 'privacy policy campaign',
            'meta_desc'   => 'Privacy policy for John Stacy for Rockwall County Commissioner Precinct 4.',
        ],
        'candidate-donate' => [
            'title'       => 'Donate',
            'desc'        => 'Donation page with embedded Anedot/ActBlue/WinRed form and mail-in info.',
            'color'       => '#E60000',
            'shortcodes'  => [
                '[atp_cand_styles]',
                '[atp_cand_nav]',
                '[atp_cand_donate_page]',
                '[atp_cand_footer]',
            ],
            'focus_kw'    => 'donate john stacy commissioner',
            'meta_desc'   => 'Support John Stacy for Rockwall County Commissioner Precinct 4. Donate online securely.',
        ],
        'candidate-contact' => [
            'title'       => 'Contact',
            'desc'        => 'Contact page with phone, email, office address, Calendly scheduling, and social links.',
            'color'       => '#0B1C33',
            'shortcodes'  => [
                '[atp_cand_styles]',
                '[atp_cand_nav]',
                '[atp_cand_contact]',
                '[atp_cand_footer]',
            ],
            'focus_kw'    => 'contact commissioner john stacy rockwall',
            'meta_desc'   => 'Contact Commissioner John Stacy — phone, email, office address, or book a meeting via Calendly.',
        ],
        'candidate-cookies' => [
            'title'       => 'Cookie & TCPA Policy',
            'desc'        => 'Cookie, tracking, DLC10 and TCPA compliance policy.',
            'color'       => '#555555',
            'shortcodes'  => [
                '[atp_cand_styles]',
                '[atp_cand_nav]',
                '[atp_cand_cookies]',
                '[atp_cand_footer]',
            ],
            'focus_kw'    => 'cookie policy TCPA DLC10',
            'meta_desc'   => 'Cookie, tracking, and TCPA/DLC10 policy for Commissioner John Stacy website.',
        ],
    ];
}

/* ─────────────────────────────────────────────────────────────────────────────
   Admin menu
   ───────────────────────────────────────────────────────────────────────── */

add_action( 'admin_menu', 'atp_importer_admin_menu' );

/**
 * Register the "Import Pages" submenu under the existing ATP Shortcodes menu.
 */
function atp_importer_admin_menu() {
    add_submenu_page(
        'atp-demo-shortcodes',          // parent slug
        'ATP Page Importer',            // page title
        'Import Pages',                 // menu title
        'manage_options',               // capability
        'atp-import-pages',             // menu slug
        'atp_importer_render_page'      // callback
    );
}

/* ─────────────────────────────────────────────────────────────────────────────
   Page renderer
   ───────────────────────────────────────────────────────────────────────── */

/**
 * Render the admin page — three cards with "Import Page" buttons.
 */
function atp_importer_render_page() {

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have permission to access this page.', 'atp-demo' ) );
    }

    // ── Handle import action ──────────────────────────────────────────────────
    $notice = '';
    if ( isset( $_POST['atp_import_page'] ) ) {
        $notice = atp_importer_handle_import();
    }

    $page_sets = atp_importer_page_sets();
    ?>
    <div class="wrap atp-importer-wrap">

        <div class="atp-importer-header">
            <img src="<?php echo esc_url( ATP_DEMO_URL . 'assets/images/ATP-Logo-Red-White.png' ); ?>"
                 alt="ATP" class="atp-importer-logo">
            <div>
                <h1><?php esc_html_e( 'ATP Page Importer', 'atp-demo' ); ?></h1>
                <p style="margin:4px 0 0;color:#ccc;">
                    Create fully-configured WordPress pages in one click. Each import sets the page content,
                    SEO metadata (Yoast-compatible), page template, and a featured image.
                </p>
            </div>
        </div>

        <?php if ( $notice ) { echo $notice; } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in handler ?>

        <div class="atp-importer-grid">
            <?php foreach ( $page_sets as $slug => $set ) :
                $existing_id = atp_importer_page_exists( $set['title'] );
            ?>
            <div class="atp-importer-card" style="--card-color:<?php echo esc_attr( $set['color'] ); ?>">
                <div class="atp-importer-card-header">
                    <h2><?php echo esc_html( $set['title'] ); ?></h2>
                    <p><?php echo esc_html( $set['desc'] ); ?></p>
                </div>
                <div class="atp-importer-card-body">
                    <div class="atp-importer-meta-row">
                        <span class="atp-importer-meta-label">Shortcodes</span>
                        <span class="atp-importer-meta-value"><?php echo count( $set['shortcodes'] ); ?></span>
                    </div>
                    <div class="atp-importer-meta-row">
                        <span class="atp-importer-meta-label">Focus keyword</span>
                        <span class="atp-importer-meta-value"><code><?php echo esc_html( $set['focus_kw'] ); ?></code></span>
                    </div>
                    <div class="atp-importer-meta-row">
                        <span class="atp-importer-meta-label">Meta description</span>
                        <span class="atp-importer-meta-value" style="font-size:12px;"><?php echo esc_html( $set['meta_desc'] ); ?></span>
                    </div>
                    <div class="atp-importer-shortcode-list">
                        <?php foreach ( $set['shortcodes'] as $sc ) : ?>
                            <code class="atp-importer-sc-chip"><?php echo esc_html( $sc ); ?></code>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="atp-importer-card-footer">
                    <?php if ( $existing_id ) : ?>
                        <span class="atp-importer-exists-badge">Already imported</span>
                        <a href="<?php echo esc_url( get_edit_post_link( $existing_id ) ); ?>" class="button">
                            Edit Page
                        </a>
                        <a href="<?php echo esc_url( get_permalink( $existing_id ) ); ?>" class="button" target="_blank">
                            View Page
                        </a>
                    <?php else : ?>
                        <form method="post">
                            <?php wp_nonce_field( 'atp_import_page_' . $slug, 'atp_import_nonce' ); ?>
                            <input type="hidden" name="atp_import_page" value="<?php echo esc_attr( $slug ); ?>">
                            <button type="submit" class="button button-primary button-hero atp-import-btn">
                                Import Page
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div><!-- .atp-importer-wrap -->

    <style>
        /* ── Importer layout ──────────────────────────────────────────────── */
        .atp-importer-wrap { max-width: 1200px; }

        .atp-importer-header {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 28px;
            padding: 20px 24px;
            background: #0B1C33;
            border-radius: 8px;
            color: #fff;
        }
        .atp-importer-header h1 { color: #fff; margin: 0; font-size: 22px; }
        .atp-importer-header p  { color: #ccc; }
        .atp-importer-logo { width: 56px; height: auto; }

        .atp-importer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 24px;
        }

        /* ── Card ─────────────────────────────────────────────────────────── */
        .atp-importer-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
        }
        .atp-importer-card-header {
            padding: 20px 22px 14px;
            background: var(--card-color);
            color: #fff;
        }
        .atp-importer-card-header h2 { color: #fff; margin: 0 0 6px; font-size: 18px; }
        .atp-importer-card-header p  { color: rgba(255,255,255,.78); margin: 0; font-size: 13px; line-height: 1.5; }

        .atp-importer-card-body { padding: 18px 22px; flex: 1; }

        .atp-importer-meta-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 5px 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 13px;
        }
        .atp-importer-meta-label { font-weight: 600; color: #333; white-space: nowrap; margin-right: 12px; }
        .atp-importer-meta-value { color: #555; text-align: right; }

        .atp-importer-shortcode-list {
            margin-top: 14px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        .atp-importer-sc-chip {
            background: #f5f3f0;
            border: 1px solid #e0dcd6;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11.5px;
            white-space: nowrap;
        }

        .atp-importer-card-footer {
            padding: 14px 22px;
            border-top: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fafafa;
        }

        .atp-import-btn {
            background: var(--card-color) !important;
            border-color: var(--card-color) !important;
        }
        .atp-import-btn:hover { opacity: .9; }
        .atp-import-btn:focus { box-shadow: 0 0 0 1px #fff, 0 0 0 3px var(--card-color) !important; }

        .atp-importer-exists-badge {
            display: inline-block;
            background: #e8f5e9;
            color: #2e7d32;
            font-weight: 600;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 4px;
        }
    </style>
    <?php
}

/* ─────────────────────────────────────────────────────────────────────────────
   Import handler
   ───────────────────────────────────────────────────────────────────────── */

/**
 * Process the import form submission.
 *
 * Validates nonce and capability, creates the page, sets SEO meta, and
 * attaches a featured image.
 *
 * @return string  HTML notice markup.
 */
function atp_importer_handle_import() {

    if ( ! current_user_can( 'manage_options' ) ) {
        return '<div class="notice notice-error"><p>Permission denied.</p></div>';
    }

    $slug      = sanitize_key( $_POST['atp_import_page'] ?? '' );
    $page_sets = atp_importer_page_sets();

    if ( ! isset( $page_sets[ $slug ] ) ) {
        return '<div class="notice notice-error"><p>Unknown page set.</p></div>';
    }

    // Verify nonce for this specific page set.
    if ( ! isset( $_POST['atp_import_nonce'] )
        || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['atp_import_nonce'] ) ), 'atp_import_page_' . $slug )
    ) {
        return '<div class="notice notice-error"><p>Security check failed. Please try again.</p></div>';
    }

    $set = $page_sets[ $slug ];

    // ── Duplicate check ───────────────────────────────────────────────────────
    if ( atp_importer_page_exists( $set['title'] ) ) {
        return '<div class="notice notice-warning"><p>A page titled <strong>'
            . esc_html( $set['title'] )
            . '</strong> already exists. Import skipped.</p></div>';
    }

    // ── Build page content ────────────────────────────────────────────────────
    $content = implode( "\n", $set['shortcodes'] );

    // ── Determine page template ───────────────────────────────────────────────
    // Always prefer Elementor Canvas if Elementor is active.
    $use_elementor = defined( 'ELEMENTOR_VERSION' );
    $template      = $use_elementor ? 'elementor_canvas' : atp_importer_detect_canvas_template();

    // ── Insert page ───────────────────────────────────────────────────────────
    $page_id = wp_insert_post( [
        'post_title'    => $set['title'],
        'post_content'  => $content,
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_author'   => get_current_user_id(),
        'page_template' => $template,
    ], true );

    if ( is_wp_error( $page_id ) ) {
        return '<div class="notice notice-error"><p>Failed to create page: '
            . esc_html( $page_id->get_error_message() ) . '</p></div>';
    }

    // ── Elementor data ────────────────────────────────────────────────────────
    if ( $use_elementor ) {
        atp_importer_set_elementor_data( $page_id, $content );
    }

    // ── Hide the page title (no "ATP Homepage" heading) ──────────────────────
    update_post_meta( $page_id, '_atp_hide_title', '1' );

    // ── SEO metadata (Yoast / generic) ────────────────────────────────────────
    atp_importer_set_seo_meta( $page_id, $set['focus_kw'], $set['meta_desc'] );

    // ── Featured image ────────────────────────────────────────────────────────
    $image_result = atp_importer_set_featured_image( $page_id, $set['focus_kw'] );
    $image_note   = '';
    if ( is_wp_error( $image_result ) ) {
        $image_note = ' <em>(Featured image could not be set: ' . esc_html( $image_result->get_error_message() ) . ')</em>';
    }

    // ── Success ───────────────────────────────────────────────────────────────
    $edit_link = get_edit_post_link( $page_id, 'raw' );
    $view_link = get_permalink( $page_id );
    $tpl_note  = $use_elementor ? ' (Elementor Canvas)' : ( $template ? " ($template)" : '' );

    return '<div class="notice notice-success is-dismissible"><p>'
        . '<strong>' . esc_html( $set['title'] ) . '</strong> imported and published' . $tpl_note . '.'
        . $image_note
        . ' &nbsp; <a href="' . esc_url( $edit_link ) . '">Edit page</a>'
        . ' | <a href="' . esc_url( $view_link ) . '" target="_blank">View page</a>'
        . '</p></div>';
}

/* ─────────────────────────────────────────────────────────────────────────────
   Helper: check for existing page by title
   ───────────────────────────────────────────────────────────────────────── */

/**
 * Look for a published or draft page with the given title.
 *
 * @param  string   $title  Page title to search for.
 * @return int|false        Post ID if found, false otherwise.
 */
function atp_importer_page_exists( $title ) {
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $id = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts}
             WHERE post_title = %s
               AND post_type  = 'page'
               AND post_status IN ('publish','draft','pending','private')
             LIMIT 1",
            $title
        )
    );

    return $id ? (int) $id : false;
}

/* ─────────────────────────────────────────────────────────────────────────────
   Helper: detect best "canvas / blank" page template
   ───────────────────────────────────────────────────────────────────────── */

/**
 * Try to find a blank / canvas / full-width template from the active theme or
 * popular page-builder plugins. Falls back to the WP default (empty string).
 *
 * @return string  Template filename or identifier.
 */
function atp_importer_detect_canvas_template() {

    // Prioritised list of well-known canvas / blank templates.
    $candidates = [
        // Elementor
        'elementor_canvas',
        // Elementor header-footer
        'elementor_header_footer',
        // Astra
        'astra-starter-template',
        'page-builder-blank',
        // GeneratePress
        'page-builder-blank.php',
        // Kadence
        'page-builder-blank',
        // OceanWP
        'templates/full-width.php',
        // Generic full-width files often shipped with themes
        'template-canvas.php',
        'template-blank.php',
        'page-templates/blank.php',
        'page-templates/canvas.php',
        'page-templates/full-width.php',
        'templates/blank.php',
        'templates/canvas.php',
        'full-width.php',
        'blank.php',
    ];

    // wp_get_theme()->get_page_templates() returns an associative array
    // where keys are template file names / identifiers.
    $available = wp_get_theme()->get_page_templates( null, 'page' );

    foreach ( $candidates as $tpl ) {
        if ( isset( $available[ $tpl ] ) ) {
            return $tpl;
        }
    }

    // No canvas template found — create one in the active theme.
    $theme_dir = get_theme_root() . '/' . get_stylesheet();
    $canvas_file = $theme_dir . '/page-canvas.php';
    if ( ! file_exists( $canvas_file ) ) {
        $canvas_php = "<?php\n/* Template Name: Canvas */\nwhile ( have_posts() ) : the_post();\n\tthe_content();\nendwhile;\n";
        file_put_contents( $canvas_file, $canvas_php );
    }
    return 'page-canvas.php';
}

/* ─────────────────────────────────────────────────────────────────────────────
   Helper: set Yoast-compatible SEO metadata
   ───────────────────────────────────────────────────────────────────────── */

/**
 * Store the focus keyword and meta description as post meta.
 *
 * Writes Yoast SEO meta keys so the data is picked up automatically if Yoast
 * is active. Also writes keys for Rank Math, All in One SEO, and SEOPress
 * as a cross-plugin fallback.
 *
 * @param int    $post_id   Post ID.
 * @param string $focus_kw  Focus keyword.
 * @param string $meta_desc Meta description.
 */
function atp_importer_set_seo_meta( $post_id, $focus_kw, $meta_desc ) {

    // Yoast SEO meta keys.
    update_post_meta( $post_id, '_yoast_wpseo_focuskw',  $focus_kw );
    update_post_meta( $post_id, '_yoast_wpseo_metadesc', $meta_desc );

    // Rank Math compatibility.
    update_post_meta( $post_id, 'rank_math_focus_keyword',    $focus_kw );
    update_post_meta( $post_id, 'rank_math_description',      $meta_desc );

    // All in One SEO compatibility.
    update_post_meta( $post_id, '_aioseo_description', $meta_desc );
    update_post_meta( $post_id, '_aioseo_keywords',    $focus_kw );

    // SEOPress compatibility.
    update_post_meta( $post_id, '_seopress_titles_desc',          $meta_desc );
    update_post_meta( $post_id, '_seopress_analysis_target_kw',   $focus_kw );

    // Generic fallback.
    update_post_meta( $post_id, '_atp_focus_keyword',  $focus_kw );
    update_post_meta( $post_id, '_atp_meta_desc',      $meta_desc );
}

/* ─────────────────────────────────────────────────────────────────────────────
   Helper: copy logo into Media Library and set as featured image
   ───────────────────────────────────────────────────────────────────────── */

/**
 * Copy the ATP logo PNG from the plugin assets directory into the WP Media
 * Library, set the focus keyword as alt text and attachment title, then
 * assign it as the post's featured image (thumbnail).
 *
 * If the attachment already exists (from a previous import) it is reused
 * rather than duplicated.
 *
 * @param  int    $post_id    The page post ID.
 * @param  string $focus_kw   The focus keyword — used as alt and title.
 * @return int|WP_Error       Attachment ID on success, WP_Error on failure.
 */
function atp_importer_set_featured_image( $post_id, $focus_kw ) {

    // Reuse an existing media-library copy if present.
    $existing_id = atp_importer_find_existing_attachment( $focus_kw );
    if ( $existing_id ) {
        set_post_thumbnail( $post_id, $existing_id );
        return $existing_id;
    }

    // Source file inside the plugin.
    $source = ATP_DEMO_DIR . 'assets/images/ATP-Logo-Standard.png';
    if ( ! file_exists( $source ) ) {
        return new WP_Error( 'missing_source', 'ATP-Logo-Standard.png not found in plugin assets.' );
    }

    // We need these WP helpers.
    if ( ! function_exists( 'wp_crop_image' ) ) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }
    if ( ! function_exists( 'media_handle_sideload' ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
    }

    // Copy to the system temp directory so wp_handle_sideload can move it.
    $tmp_file = wp_tempnam( 'atp-logo-' );
    // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_copy
    if ( ! copy( $source, $tmp_file ) ) {
        return new WP_Error( 'copy_failed', 'Could not copy logo to temporary file.' );
    }

    // Build the file array expected by media_handle_sideload.
    $file_array = [
        'name'     => 'atp-logo-standard.png',
        'type'     => 'image/png',
        'tmp_name' => $tmp_file,
        'error'    => 0,
        'size'     => filesize( $tmp_file ),
    ];

    // Sideload into the media library (moves the file into wp-content/uploads).
    $attach_id = media_handle_sideload( $file_array, $post_id, $focus_kw );

    // Clean up temp file if sideload failed.
    if ( is_wp_error( $attach_id ) ) {
        // phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
        @unlink( $tmp_file );
        return $attach_id;
    }

    // Set attachment title and alt text to the focus keyword.
    wp_update_post( [
        'ID'         => $attach_id,
        'post_title' => $focus_kw,
    ] );
    update_post_meta( $attach_id, '_wp_attachment_image_alt', $focus_kw );

    // Assign as featured image.
    set_post_thumbnail( $post_id, $attach_id );

    return $attach_id;
}

/**
 * Look for an existing media-library attachment whose alt text matches
 * the given focus keyword so we can reuse it instead of duplicating.
 *
 * @param  string    $focus_kw  Focus keyword / alt text to search for.
 * @return int|false            Attachment ID or false.
 */
function atp_importer_find_existing_attachment( $focus_kw ) {
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $id = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT p.ID
             FROM {$wpdb->posts} p
             INNER JOIN {$wpdb->postmeta} pm ON pm.post_id = p.ID
             WHERE p.post_type   = 'attachment'
               AND p.post_status = 'inherit'
               AND pm.meta_key   = '_wp_attachment_image_alt'
               AND pm.meta_value = %s
             LIMIT 1",
            $focus_kw
        )
    );

    return $id ? (int) $id : false;
}

/* ─────────────────────────────────────────────────────────────────────────────
   Helper: set Elementor page data
   ───────────────────────────────────────────────────────────────────────── */

/**
 * Write Elementor meta so the page renders as an Elementor Canvas page with
 * an HTML widget containing all the shortcodes. This avoids the theme's
 * header/footer and title entirely.
 *
 * @param int    $post_id Post ID.
 * @param string $content Shortcode content (newline-separated).
 */
function atp_importer_set_elementor_data( $post_id, $content ) {

    // Build Elementor JSON structure: one section → one column → one HTML widget.
    $elementor_data = [
        [
            'id'       => wp_generate_password( 7, false ),
            'elType'   => 'section',
            'settings' => [
                'layout'          => 'full_width',
                'content_width'   => 'full',
                'gap'             => 'no',
                'padding'         => [ 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px', 'isLinked' => false ],
                'margin'          => [ 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px', 'isLinked' => false ],
            ],
            'elements' => [
                [
                    'id'       => wp_generate_password( 7, false ),
                    'elType'   => 'column',
                    'settings' => [
                        '_column_size' => 100,
                        'padding'      => [ 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px', 'isLinked' => false ],
                    ],
                    'elements' => [
                        [
                            'id'         => wp_generate_password( 7, false ),
                            'elType'     => 'widget',
                            'widgetType' => 'html',
                            'settings'   => [
                                'html' => $content,
                            ],
                            'elements'   => [],
                        ],
                    ],
                ],
            ],
        ],
    ];

    // Set Elementor post meta.
    update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $elementor_data ) ) );
    update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
    update_post_meta( $post_id, '_elementor_template_type', 'wp-page' );
    update_post_meta( $post_id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0' );
    update_post_meta( $post_id, '_wp_page_template', 'elementor_canvas' );

    // Clear Elementor CSS cache for this post so it regenerates.
    delete_post_meta( $post_id, '_elementor_css' );
}

/* ═════════════════════════════════════════════════════════════════════════════
   SITE-WIDE HEADER / FOOTER SHORTCODE INJECTION
   ═════════════════════════════════════════════════════════════════════════ */

/**
 * Register settings for site-wide header/footer shortcodes.
 */
add_action( 'admin_init', 'atp_headerfooter_register_settings' );
function atp_headerfooter_register_settings() {
    register_setting( 'atp_headerfooter', 'atp_sitewide_header', [ 'default' => '' ] );
    register_setting( 'atp_headerfooter', 'atp_sitewide_footer', [ 'default' => '' ] );
}

/**
 * Add "Header & Footer" submenu under ATP Shortcodes.
 */
add_action( 'admin_menu', 'atp_headerfooter_menu' );
function atp_headerfooter_menu() {
    add_submenu_page(
        'atp-demo-shortcodes',
        'Header & Footer',
        'Header & Footer',
        'manage_options',
        'atp-header-footer',
        'atp_headerfooter_page'
    );
}

/**
 * Render the header/footer settings page.
 */
function atp_headerfooter_page() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    if ( isset( $_POST['atp_hf_save'] ) && check_admin_referer( 'atp_hf_save' ) ) {
        update_option( 'atp_sitewide_header', wp_unslash( $_POST['atp_sitewide_header'] ?? '' ) );
        update_option( 'atp_sitewide_footer', wp_unslash( $_POST['atp_sitewide_footer'] ?? '' ) );
        echo '<div class="notice notice-success is-dismissible"><p>Header & Footer settings saved.</p></div>';
    }

    $header_val = get_option( 'atp_sitewide_header', '[atp_hp_pollbar][atp_hp_header]' );
    $footer_val = get_option( 'atp_sitewide_footer', '[atp_hp_footer]' );
    ?>
    <div class="wrap atp-admin-wrap">
        <div class="atp-admin-header" style="display:flex;align-items:center;gap:20px;background:#0B1C33;color:#fff;padding:20px 28px;border-radius:8px;margin:20px 0 28px">
            <img src="<?php echo esc_url( ATP_DEMO_URL . 'assets/images/ATP-Logo-Red-White.png' ); ?>" alt="ATP" style="width:56px;height:auto">
            <div>
                <h1 style="color:#fff;margin:0">Site-Wide Header & Footer</h1>
                <p style="color:#ccc;margin:4px 0 0">Enter shortcodes to inject on every page/post. Individual pages can opt out via the "ATP Display Options" meta box.</p>
            </div>
        </div>
        <form method="post">
            <?php wp_nonce_field( 'atp_hf_save' ); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="atp_sitewide_header">Header Shortcodes</label></th>
                    <td>
                        <textarea name="atp_sitewide_header" id="atp_sitewide_header" rows="3" class="large-text code"><?php echo esc_textarea( $header_val ); ?></textarea>
                        <p class="description">Rendered at the top of the page body. Example: <code>[atp_hp_pollbar][atp_hp_header]</code></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="atp_sitewide_footer">Footer Shortcodes</label></th>
                    <td>
                        <textarea name="atp_sitewide_footer" id="atp_sitewide_footer" rows="3" class="large-text code"><?php echo esc_textarea( $footer_val ); ?></textarea>
                        <p class="description">Rendered at the bottom of the page body. Example: <code>[atp_hp_footer]</code></p>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <button type="submit" name="atp_hf_save" class="button button-primary">Save Header & Footer</button>
            </p>
        </form>
    </div>
    <?php
}

/**
 * Inject site-wide header shortcodes after <body>.
 */
add_action( 'wp_body_open', 'atp_inject_sitewide_header', 1 );
function atp_inject_sitewide_header() {
    if ( is_admin() ) return;

    $post_id = get_the_ID();

    // Check per-page opt-out.
    if ( $post_id && get_post_meta( $post_id, '_atp_hide_header', true ) === '1' ) {
        return;
    }

    $header = get_option( 'atp_sitewide_header', '' );
    if ( $header ) {
        echo do_shortcode( $header );
    }
}

/**
 * Inject site-wide footer shortcodes before </body>.
 */
add_action( 'wp_footer', 'atp_inject_sitewide_footer', 99 );
function atp_inject_sitewide_footer() {
    if ( is_admin() ) return;

    $post_id = get_the_ID();

    // Check per-page opt-out.
    if ( $post_id && get_post_meta( $post_id, '_atp_hide_footer', true ) === '1' ) {
        return;
    }

    $footer = get_option( 'atp_sitewide_footer', '' );
    if ( $footer ) {
        echo do_shortcode( $footer );
    }
}

/* ═════════════════════════════════════════════════════════════════════════════
   PER-PAGE/POST DISPLAY OPTIONS META BOX
   ═════════════════════════════════════════════════════════════════════════ */

/**
 * Register the "ATP Display Options" meta box on all public post types.
 */
add_action( 'add_meta_boxes', 'atp_display_options_metabox' );
function atp_display_options_metabox() {
    $post_types = get_post_types( [ 'public' => true ], 'names' );
    foreach ( $post_types as $pt ) {
        add_meta_box(
            'atp_display_options',
            'ATP Display Options',
            'atp_display_options_render',
            $pt,
            'side',
            'default'
        );
    }
}

/**
 * Render the meta box with checkboxes for hiding header, footer, and title.
 */
function atp_display_options_render( $post ) {
    wp_nonce_field( 'atp_display_opts', 'atp_display_opts_nonce' );

    $hide_header = get_post_meta( $post->ID, '_atp_hide_header', true );
    $hide_footer = get_post_meta( $post->ID, '_atp_hide_footer', true );
    $hide_title  = get_post_meta( $post->ID, '_atp_hide_title', true );
    ?>
    <p style="margin:0 0 8px"><strong>Site-wide elements</strong></p>
    <label style="display:block;margin-bottom:6px">
        <input type="checkbox" name="_atp_hide_header" value="1" <?php checked( $hide_header, '1' ); ?>>
        Hide site-wide header on this page
    </label>
    <label style="display:block;margin-bottom:6px">
        <input type="checkbox" name="_atp_hide_footer" value="1" <?php checked( $hide_footer, '1' ); ?>>
        Hide site-wide footer on this page
    </label>
    <hr style="margin:10px 0">
    <label style="display:block;margin-bottom:4px">
        <input type="checkbox" name="_atp_hide_title" value="1" <?php checked( $hide_title, '1' ); ?>>
        Hide page title
    </label>
    <p class="description" style="margin-top:6px">Use for canvas/shortcode pages that have their own header, footer, or title built in.</p>
    <?php
}

/**
 * Save the meta box values.
 */
add_action( 'save_post', 'atp_display_options_save' );
function atp_display_options_save( $post_id ) {
    if ( ! isset( $_POST['atp_display_opts_nonce'] )
        || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['atp_display_opts_nonce'] ) ), 'atp_display_opts' )
    ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $fields = [ '_atp_hide_header', '_atp_hide_footer', '_atp_hide_title' ];
    foreach ( $fields as $key ) {
        if ( isset( $_POST[ $key ] ) && $_POST[ $key ] === '1' ) {
            update_post_meta( $post_id, $key, '1' );
        } else {
            delete_post_meta( $post_id, $key );
        }
    }
}

/**
 * Hide the page title via CSS when _atp_hide_title is set.
 */
add_action( 'wp_head', 'atp_maybe_hide_title' );
function atp_maybe_hide_title() {
    if ( is_admin() ) return;

    $post_id = get_the_ID();
    if ( $post_id && get_post_meta( $post_id, '_atp_hide_title', true ) === '1' ) {
        echo '<style>.entry-title,.page-title,.post-title,h1.wp-block-post-title{display:none!important}</style>' . "\n";
    }
}
