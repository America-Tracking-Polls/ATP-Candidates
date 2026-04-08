<?php
/**
 * ATP Page Importer — one-click page creation for Homepage, Brand Guide, and Intake Form.
 *
 * Creates WordPress pages populated with shortcodes, sets SEO meta (Yoast/RankMath compatible),
 * and attaches a featured image with the focus keyword as alt text and title.
 *
 * @package ATPDemo
 * @since   1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ════════════════════════════════════════════════════════════════
   PAGE DEFINITIONS
════════════════════════════════════════════════════════════════ */

function atp_importer_get_pages() {
    return [
        'homepage' => [
            'title'         => 'ATP Homepage',
            'color'         => '#0B1C33',
            'icon'          => 'dashicons-admin-home',
            'desc'          => 'Full marketing homepage with hero, survey, pipeline, AEO, and footer.',
            'shortcodes'    => [
                '[atp_hp_styles]',
                '[atp_hp_pollbar]',
                '[atp_hp_header]',
                '[atp_hp_hero]',
                '[atp_hp_about]',
                '[atp_hp_survey]',
                '[atp_hp_journey]',
                '[atp_hp_pipeline]',
                '[atp_hp_aeo]',
                '[atp_hp_trust]',
                '[atp_hp_intake]',
                '[atp_hp_footer]',
                '[atp_hp_scripts]',
            ],
            'focus_keyword' => 'polling powered campaign marketing',
            'meta_desc'     => 'ATP delivers polling powered campaign marketing with synchronized multi-channel solutions for local, statewide, and federal races.',
            'slug'          => 'home',
        ],
        'brand_guide' => [
            'title'         => 'ATP Brand Guide',
            'color'         => '#B22234',
            'icon'          => 'dashicons-art',
            'desc'          => 'Full brand guide with colors, typography, logos, tone, and CTAs.',
            'shortcodes'    => [
                '[atp_brand_styles]',
                '[atp_brand_nav]',
                '[atp_brand_hero]',
                '[atp_brand_bio]',
                '[atp_brand_colors]',
                '[atp_brand_typography]',
                '[atp_brand_logos]',
                '[atp_brand_imagery]',
                '[atp_brand_animation]',
                '[atp_brand_tone]',
                '[atp_brand_cta]',
                '[atp_brand_footer]',
                '[atp_brand_scripts]',
            ],
            'focus_keyword' => 'america tracking polls brand guide',
            'meta_desc'     => 'The official America Tracking Polls brand guide — colors, typography, logos, tone of voice, and visual identity standards.',
            'slug'          => 'brand-guide',
        ],
        'intake' => [
            'title'         => 'Candidate Intake Form',
            'color'         => '#2E2D5A',
            'icon'          => 'dashicons-clipboard',
            'desc'          => 'Multi-step candidate onboarding form with 18 sections.',
            'shortcodes'    => [
                '[atp_intake]',
            ],
            'focus_keyword' => 'candidate intake form',
            'meta_desc'     => 'Complete the candidate intake form to get started with America Tracking Polls campaign services.',
            'slug'          => 'candidate-intake',
        ],
    ];
}

/* ════════════════════════════════════════════════════════════════
   ADMIN MENU
════════════════════════════════════════════════════════════════ */

add_action( 'admin_menu', 'atp_importer_menu' );

function atp_importer_menu() {
    add_submenu_page(
        'atp-demo-shortcodes',
        'Import Pages',
        'Import Pages',
        'manage_options',
        'atp-import-pages',
        'atp_importer_page'
    );
}

/* ════════════════════════════════════════════════════════════════
   HANDLE IMPORT ACTION
════════════════════════════════════════════════════════════════ */

function atp_importer_handle_import( $page_key ) {
    $pages = atp_importer_get_pages();

    if ( ! isset( $pages[ $page_key ] ) ) {
        return new WP_Error( 'invalid_page', 'Unknown page key.' );
    }

    $page = $pages[ $page_key ];

    // Check for duplicate.
    $existing = get_page_by_path( $page['slug'], OBJECT, 'page' );
    if ( $existing ) {
        return new WP_Error( 'duplicate', sprintf(
            'A page with the slug "%s" already exists. <a href="%s">Edit it here</a>.',
            esc_html( $page['slug'] ),
            esc_url( get_edit_post_link( $existing->ID ) )
        ) );
    }

    // Build page content from shortcodes.
    $content = implode( "\n", $page['shortcodes'] );

    // Detect the best page template for a canvas/full-width layout.
    $template = atp_importer_detect_template();

    // Create the page.
    $post_id = wp_insert_post( [
        'post_title'   => $page['title'],
        'post_name'    => $page['slug'],
        'post_content' => $content,
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'page_template' => $template,
    ], true );

    if ( is_wp_error( $post_id ) ) {
        return $post_id;
    }

    // Set SEO meta — Yoast SEO.
    update_post_meta( $post_id, '_yoast_wpseo_focuskw', $page['focus_keyword'] );
    update_post_meta( $post_id, '_yoast_wpseo_metadesc', $page['meta_desc'] );
    update_post_meta( $post_id, '_yoast_wpseo_title', $page['title'] . ' — America Tracking Polls' );

    // Set SEO meta — RankMath (if installed).
    update_post_meta( $post_id, 'rank_math_focus_keyword', $page['focus_keyword'] );
    update_post_meta( $post_id, 'rank_math_description', $page['meta_desc'] );
    update_post_meta( $post_id, 'rank_math_title', $page['title'] . ' — America Tracking Polls' );

    // Set SEO meta — All in One SEO.
    update_post_meta( $post_id, '_aioseo_description', $page['meta_desc'] );
    update_post_meta( $post_id, '_aioseo_keywords', $page['focus_keyword'] );

    // Attach featured image.
    $image_id = atp_importer_attach_featured_image( $post_id, $page['focus_keyword'] );
    if ( $image_id && ! is_wp_error( $image_id ) ) {
        set_post_thumbnail( $post_id, $image_id );
    }

    return $post_id;
}

/* ════════════════════════════════════════════════════════════════
   FEATURED IMAGE
════════════════════════════════════════════════════════════════ */

function atp_importer_attach_featured_image( $post_id, $focus_keyword ) {
    $source = ATP_DEMO_DIR . 'assets/images/ATP-Logo-Standard.png';

    if ( ! file_exists( $source ) ) {
        return false;
    }

    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $upload_dir = wp_upload_dir();
    $filename   = 'atp-' . sanitize_title( $focus_keyword ) . '.png';
    $dest       = $upload_dir['path'] . '/' . $filename;

    // Copy the logo to the uploads directory.
    if ( ! copy( $source, $dest ) ) {
        return false;
    }

    $filetype = wp_check_filetype( $dest );

    $attachment_id = wp_insert_attachment( [
        'guid'           => $upload_dir['url'] . '/' . $filename,
        'post_mime_type' => $filetype['type'],
        'post_title'     => $focus_keyword,
        'post_content'   => '',
        'post_status'    => 'inherit',
    ], $dest, $post_id );

    if ( is_wp_error( $attachment_id ) ) {
        return $attachment_id;
    }

    $metadata = wp_generate_attachment_metadata( $attachment_id, $dest );
    wp_update_attachment_metadata( $attachment_id, $metadata );

    // Set alt text to focus keyword.
    update_post_meta( $attachment_id, '_wp_attachment_image_alt', $focus_keyword );

    return $attachment_id;
}

/* ════════════════════════════════════════════════════════════════
   TEMPLATE DETECTION
════════════════════════════════════════════════════════════════ */

function atp_importer_detect_template() {
    // Common canvas / full-width templates across popular themes and page builders.
    $candidates = [
        'elementor_canvas',                        // Elementor Canvas
        'elementor-canvas',                        // Elementor Canvas (alt)
        'templates/canvas.php',                    // GeneratePress / Astra canvas
        'page-templates/blank.php',                // OceanWP blank
        'template-canvas.php',                     // Kadence / generic canvas
        'template-blank.php',                      // Generic blank
        'page-templates/full-width.php',           // Common full-width
        'template-full-width.php',                 // Generic full-width
        'templates/full-width.php',                // Astra full-width
        'page-templates/page-fullwidth.php',       // Theme full-width
    ];

    $theme_templates = wp_get_theme()->get_page_templates();

    foreach ( $candidates as $tpl ) {
        if ( isset( $theme_templates[ $tpl ] ) ) {
            return $tpl;
        }
    }

    // Fallback: use the first available template, or default.
    return 'default';
}

/* ════════════════════════════════════════════════════════════════
   ADMIN PAGE
════════════════════════════════════════════════════════════════ */

function atp_importer_page() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    $message = '';
    $error   = '';

    // Handle import request.
    if ( isset( $_POST['atp_import_page'] ) && check_admin_referer( 'atp_import_page' ) ) {
        $key    = sanitize_key( $_POST['atp_page_key'] ?? '' );
        $result = atp_importer_handle_import( $key );

        if ( is_wp_error( $result ) ) {
            $error = $result->get_error_message();
        } elseif ( is_int( $result ) ) {
            $pages   = atp_importer_get_pages();
            $title   = $pages[ $key ]['title'] ?? 'Page';
            $message = sprintf(
                '<strong>%s</strong> created successfully! <a href="%s">View page</a> | <a href="%s">Edit page</a>',
                esc_html( $title ),
                esc_url( get_permalink( $result ) ),
                esc_url( get_edit_post_link( $result ) )
            );
        }
    }

    $pages = atp_importer_get_pages();
    ?>
    <div class="wrap atp-admin-wrap">
        <div class="atp-admin-header">
            <img src="<?php echo esc_url( ATP_DEMO_URL . 'assets/images/ATP-Logo-Red-White.png' ); ?>" alt="ATP" class="atp-admin-logo">
            <div class="atp-header-text">
                <h1>Import ATP Pages</h1>
                <p>One-click page creation. Each page is populated with all shortcodes, SEO meta (focus keyword + meta description), and a featured image. Works with Elementor Canvas, blank templates, or any full-width layout.</p>
            </div>
        </div>

        <?php if ( $message ) : ?>
            <div class="notice notice-success is-dismissible"><p><?php echo wp_kses_post( $message ); ?></p></div>
        <?php endif; ?>
        <?php if ( $error ) : ?>
            <div class="notice notice-error is-dismissible"><p><?php echo wp_kses_post( $error ); ?></p></div>
        <?php endif; ?>

        <div class="atp-page-setups" style="margin-top:20px">
            <?php foreach ( $pages as $key => $page ) :
                $existing = get_page_by_path( $page['slug'], OBJECT, 'page' );
            ?>
            <div class="atp-page-box" style="--page-color:<?php echo esc_attr( $page['color'] ); ?>">
                <div class="atp-page-box-header">
                    <div>
                        <strong class="atp-page-box-title">
                            <span class="dashicons <?php echo esc_attr( $page['icon'] ); ?>" style="margin-right:6px;font-size:16px;line-height:1.4"></span>
                            <?php echo esc_html( $page['title'] ); ?>
                        </strong>
                        <p class="atp-page-box-desc"><?php echo esc_html( $page['desc'] ); ?></p>
                    </div>
                </div>
                <div style="padding:16px 18px;background:#fff">
                    <table style="width:100%;font-size:12px;color:#555;border-collapse:collapse">
                        <tr>
                            <td style="padding:4px 0;font-weight:600;width:120px;color:#333">Shortcodes</td>
                            <td style="padding:4px 0"><code style="font-size:11px"><?php echo esc_html( count( $page['shortcodes'] ) ); ?> shortcodes</code></td>
                        </tr>
                        <tr>
                            <td style="padding:4px 0;font-weight:600;color:#333">Focus Keyword</td>
                            <td style="padding:4px 0"><?php echo esc_html( $page['focus_keyword'] ); ?></td>
                        </tr>
                        <tr>
                            <td style="padding:4px 0;font-weight:600;color:#333">Meta Description</td>
                            <td style="padding:4px 0;font-style:italic;color:#888"><?php echo esc_html( substr( $page['meta_desc'], 0, 80 ) ); ?>&hellip;</td>
                        </tr>
                        <tr>
                            <td style="padding:4px 0;font-weight:600;color:#333">Slug</td>
                            <td style="padding:4px 0"><code>/<?php echo esc_html( $page['slug'] ); ?>/</code></td>
                        </tr>
                        <tr>
                            <td style="padding:4px 0;font-weight:600;color:#333">Featured Image</td>
                            <td style="padding:4px 0">ATP Logo (alt: "<?php echo esc_html( $page['focus_keyword'] ); ?>")</td>
                        </tr>
                    </table>
                    <div style="margin-top:14px;padding-top:14px;border-top:1px solid #eee;display:flex;align-items:center;gap:12px">
                        <?php if ( $existing ) : ?>
                            <span style="color:#00a32a;font-size:13px;font-weight:600">&#10003; Already imported</span>
                            <a href="<?php echo esc_url( get_permalink( $existing->ID ) ); ?>" class="button" style="margin-left:auto">View Page</a>
                            <a href="<?php echo esc_url( get_edit_post_link( $existing->ID ) ); ?>" class="button">Edit Page</a>
                        <?php else : ?>
                            <form method="post" style="margin-left:auto">
                                <?php wp_nonce_field( 'atp_import_page' ); ?>
                                <input type="hidden" name="atp_page_key" value="<?php echo esc_attr( $key ); ?>">
                                <button type="submit" name="atp_import_page" class="button button-primary" onclick="return confirm('Create the <?php echo esc_js( $page['title'] ); ?> page?')">
                                    Import Page
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div style="margin-top:30px;padding:20px;background:#f9f7f5;border:1px solid #e5e5e5;border-radius:6px;font-size:13px;color:#555;line-height:1.7">
            <strong style="color:#333">How it works:</strong>
            <ul style="margin:8px 0 0 18px;padding:0">
                <li>Each page is created with shortcodes as the content &mdash; just like pasting them into an Elementor Canvas page.</li>
                <li>The focus keyword is saved as Yoast, RankMath, and AIOSEO meta for maximum compatibility.</li>
                <li>A featured image is uploaded with the focus keyword as both the alt text and title attribute.</li>
                <li>The importer detects Canvas/blank templates automatically (Elementor, GeneratePress, Astra, Kadence, etc.).</li>
                <li>If a page with the same slug already exists, the import button is replaced with View/Edit links.</li>
            </ul>
        </div>
    </div>
    <?php
}
