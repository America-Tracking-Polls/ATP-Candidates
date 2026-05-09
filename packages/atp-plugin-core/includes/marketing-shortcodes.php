<?php
/**
 * ATP Marketing Shortcodes — [atp_mkt_*]
 *
 * Powers the ATP marketing site (hero, pipeline, AEO box, intake CTA,
 * etc.) using the same shortcode/override pattern as the candidate-site
 * shortcodes ([atp_cand_*]). Default content for each shortcode lives in
 * `packages/atp-plugin-core/templates/marketing/<file>`. WP-options
 * overrides take precedence (key: `atp_mkt_sc_<tag>`).
 *
 * On activation, three WP pages are created with shortcode markup:
 *   • marketing               (the homepage, composed of all 13 sections)
 *   • marketing-brand-guide   (placeholder until brand guide is shortcoded)
 *   • marketing-demo-hub      (placeholder until demo hub is shortcoded)
 *
 * @package ATP
 * @since   3.3.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

const ATP_MKT_TEMPLATES_DIR = 'templates/marketing/';

/* ─────────────────────────────────────────────────────────────────────────
   Registry: tag => [ template file, wrapOpen, wrapClose, label, description ]
   ───────────────────────────────────────────────────────────────────────── */

function atp_mkt_registry() {
    return [
        'atp_mkt_styles'     => [ 'styles.html',   '<style>',   '</style>',  'Global styles',               'All CSS for the marketing site. Include once per page, before any section shortcodes.' ],
        'atp_mkt_poll_bar'   => [ 'poll-bar.html', '',          '',          'Animated header strip',       '4-item rotating ticker at the top of the page.' ],
        'atp_mkt_header'     => [ 'header.html',   '',          '',          'Top navigation header',       'ATP brand mark, primary nav, "Get Started" button.' ],
        'atp_mkt_hero'       => [ 'hero.html',     '',          '',          'Hero section',                '"WIN YOUR ELECTION BEFORE ELECTION DAY" + body + video + Typeform area.' ],
        'atp_mkt_about'      => [ 'about.html',    '',          '',          'About section',               '"5 coordinated multi-media channels" headline + body.' ],
        'atp_mkt_survey'     => [ 'survey.html',   '',          '',          'Survey simulation',           'Schedule CTA, iPhone SMS-to-form mockup, "What You Learn / How It Powers Your Campaign".' ],
        'atp_mkt_journey'    => [ 'journey.html',  '',          '',          'Strategic Path',              'Five-step journey: Intelligence, AEO Integration, Engagement, Analysis, Conversion.' ],
        'atp_mkt_pipeline'   => [ 'pipeline.html', '',          '',          'Converting Data Into Action', 'Pipeline diagram: Web / Ads / QR Print / MMS / AEO branches.' ],
        'atp_mkt_aeo'        => [ 'aeo.html',      '',          '',          'AEO / ChatGPT box',           '"Voting Line Reality" + animated ChatGPT response mockup.' ],
        'atp_mkt_compliance' => [ 'compliance.html','',         '',          'Compliance & Trust',          'TCPA & 10DLC, AI Ethics Pledge, Data Privacy cards.' ],
        'atp_mkt_intake'     => [ 'intake.html',   '',          '',          'Intake / consult CTA',        '"Get Started With ATP" + Typeform placeholder + phone/email/samples link.' ],
        'atp_mkt_footer'     => [ 'footer.html',   '',          '',          'Site footer',                 'ATP brand strip with phone/email/samples + compliance links.' ],
        'atp_mkt_scripts'    => [ 'scripts.js',    '<script>',  '</script>', 'Front-end scripts',           'GSAP animations + canvas chart + ticker JS. Include once per page, near </body>.' ],
    ];
}

/* ─────────────────────────────────────────────────────────────────────────
   Renderer + shortcode registration
   ───────────────────────────────────────────────────────────────────────── */

/**
 * Marketing shortcode renderer with the same override system as
 * candidate-side shortcodes:
 *   - Per-site override: wp_options.atp_mkt_sc_<tag>  (string)
 *   - Disable toggle:    wp_options.atp_mkt_sc_<tag>_disabled (bool)
 *   - source="..."       attribute forces a specific source for preview
 *
 * (Marketing site doesn't currently use {{token}} substitution so the
 *  data-patch path is wired but a no-op here. Adding tokens to a
 *  marketing template will Just Work once the templates themselves use
 *  {{tokens}} — same data-patch flow as candidate side.)
 */
function atp_mkt_render_shortcode( $tag, $source = '' ) {
    $reg = atp_mkt_registry();
    if ( ! isset( $reg[ $tag ] ) ) return '';
    $row    = $reg[ $tag ];
    $file   = $row[0];
    $open   = $row[1];
    $close  = $row[2];

    $override = get_option( 'atp_mkt_sc_' . $tag, '' );
    $disabled = (bool) get_option( 'atp_mkt_sc_' . $tag . '_disabled', false );
    $path     = ATP_DEMO_DIR . ATP_MKT_TEMPLATES_DIR . $file;
    $default  = is_readable( $path ) ? file_get_contents( $path ) : '';

    if ( $source === 'core' ) {
        $content = $default;
    } elseif ( $source === 'override' ) {
        $content = is_string( $override ) && $override !== '' ? $override : $default;
    } elseif ( $disabled || ! is_string( $override ) || $override === '' ) {
        $content = $default;
    } else {
        $content = $override;
    }

    return $open . $content . $close;
}

add_action( 'init', 'atp_mkt_register_shortcodes' );
function atp_mkt_register_shortcodes() {
    foreach ( array_keys( atp_mkt_registry() ) as $tag ) {
        add_shortcode( $tag, function( $atts ) use ( $tag ) {
            $atts = shortcode_atts( [ 'source' => '' ], (array) $atts, $tag );
            return atp_mkt_render_shortcode( $tag, $atts['source'] );
        } );
    }
}

/* ─────────────────────────────────────────────────────────────────────────
   Marketing page creation on plugin activation
   (separate from candidate-site page importer; both can run on the same
    install — ATP's host gets both, candidate hosts only get the candidate
    importer via the existing flow.)
   ───────────────────────────────────────────────────────────────────────── */

register_activation_hook( ATP_DEMO_DIR . 'atp-demo-plugin.php', 'atp_mkt_create_pages' );
function atp_mkt_create_pages() {
    $pages = [
        'marketing' => [
            'title'      => 'ATP Marketing Home',
            'front_page' => false, // intake landing remains the front page on the candidate-platform side
            'content'    => atp_mkt_compose_homepage(),
        ],
        'marketing-brand-guide' => [
            'title'      => 'ATP Brand Guide',
            'front_page' => false,
            'content'    => '[atp_mkt_styles]<p style="padding:40px;font-family:sans-serif">Brand guide is not yet shortcoded — see the static <code>brand-guide.html</code> in the original atp-website branch for the canonical version.</p>',
        ],
        'marketing-demo-hub' => [
            'title'      => 'ATP Demo Hub',
            'front_page' => false,
            'content'    => '[atp_mkt_styles]<p style="padding:40px;font-family:sans-serif">Demo hub is not yet shortcoded — see the static <code>index.html</code> in the original atp-website branch for the canonical version.</p>',
        ],
    ];
    foreach ( $pages as $slug => $info ) {
        if ( get_page_by_path( $slug ) ) continue;
        $pid = wp_insert_post( [
            'post_title'   => $info['title'],
            'post_name'    => $slug,
            'post_content' => $info['content'],
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_author'  => 1,
        ] );
        if ( $pid && ! is_wp_error( $pid ) && ! empty( $info['front_page'] ) ) {
            update_option( 'show_on_front', 'page' );
            update_option( 'page_on_front', $pid );
        }
    }
}

function atp_mkt_compose_homepage() {
    return implode( "\n", [
        '[atp_mkt_styles]',
        '[atp_mkt_poll_bar]',
        '[atp_mkt_header]',
        '[atp_mkt_hero]',
        '[atp_mkt_about]',
        '[atp_mkt_survey]',
        '[atp_mkt_journey]',
        '[atp_mkt_pipeline]',
        '[atp_mkt_aeo]',
        '[atp_mkt_compliance]',
        '[atp_mkt_intake]',
        '[atp_mkt_footer]',
        '[atp_mkt_scripts]',
    ] );
}

/* ─────────────────────────────────────────────────────────────────────────
   Admin: marketing shortcode library + editor (sibling to the candidate
   shortcode editor; lives under the existing ATP admin menu).
   ───────────────────────────────────────────────────────────────────────── */

add_action( 'admin_menu', 'atp_mkt_admin_menu', 12 );
function atp_mkt_admin_menu() {
    add_submenu_page( 'atp-demo-shortcodes', 'Marketing Shortcodes', 'Marketing Shortcodes', 'manage_options', 'atp-mkt-edit', 'atp_mkt_admin_edit_page' );
}

function atp_mkt_admin_edit_page() {
    if ( ! current_user_can( 'manage_options' ) ) return;
    $reg = atp_mkt_registry();
    $sel = isset( $_GET['sc'] ) ? sanitize_key( $_GET['sc'] ) : '';
    if ( ! isset( $reg[ $sel ] ) ) $sel = array_key_first( $reg );

    if ( isset( $_POST['atp_mkt_save'] ) && check_admin_referer( 'atp_mkt_edit_' . $sel ) ) {
        $val      = wp_unslash( $_POST['atp_mkt_content'] ?? '' );
        $disabled = ! empty( $_POST['atp_mkt_disabled'] );
        update_option( 'atp_mkt_sc_' . $sel, $val );
        if ( $disabled ) {
            update_option( 'atp_mkt_sc_' . $sel . '_disabled', 1 );
        } else {
            delete_option( 'atp_mkt_sc_' . $sel . '_disabled' );
        }
        echo '<div class="notice notice-success is-dismissible"><p>Saved.</p></div>';
    }
    if ( isset( $_POST['atp_mkt_reset'] ) && check_admin_referer( 'atp_mkt_edit_' . $sel ) ) {
        delete_option( 'atp_mkt_sc_' . $sel );
        delete_option( 'atp_mkt_sc_' . $sel . '_disabled' );
        echo '<div class="notice notice-success is-dismissible"><p>Reverted to template default (override + toggle cleared).</p></div>';
    }

    $stored        = get_option( 'atp_mkt_sc_' . $sel, null );
    $is_disabled   = (bool) get_option( 'atp_mkt_sc_' . $sel . '_disabled', false );
    $is_override   = is_string( $stored ) && $stored !== '';
    $current       = $is_override ? $stored : @file_get_contents( ATP_DEMO_DIR . ATP_MKT_TEMPLATES_DIR . $reg[ $sel ][0] );
    $home        = get_page_by_path( 'marketing' );
    ?>
    <div class="wrap" style="max-width:1100px">
        <h1>Marketing Shortcodes</h1>
        <p>13 shortcodes that render the ATP marketing site. Each shortcode's default content lives in <code>packages/atp-plugin-core/templates/marketing/&lt;file&gt;</code>; saving here writes a WP option that overrides the default.</p>
        <?php if ( $home ) : ?>
            <p><a href="<?php echo esc_url( get_permalink( $home->ID ) ); ?>" target="_blank" class="button button-primary">Open marketing home page</a></p>
        <?php endif; ?>

        <form method="get" style="margin:16px 0">
            <input type="hidden" name="page" value="atp-mkt-edit">
            <select name="sc" onchange="this.form.submit()">
                <?php foreach ( $reg as $t => $row ) : ?>
                    <option value="<?php echo esc_attr( $t ); ?>" <?php selected( $t, $sel ); ?>>
                        [<?php echo esc_html( $t ); ?>] — <?php echo esc_html( $row[3] ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <p><strong>Status:</strong>
            <?php if ( $is_override ) : ?>
                <span style="color:#cc8400">⚑ overridden</span> — content below comes from the WP option, not the template file
            <?php else : ?>
                <span style="color:#1a7f37">✓ default</span> — content below comes from <code>templates/marketing/<?php echo esc_html( $reg[ $sel ][0] ); ?></code>
            <?php endif; ?>
        </p>

        <p style="font-size:12px;color:#666">
            Preview shortcodes:
            <code>[<?php echo esc_html( $sel ); ?> source="core"]</code>
            (forces template default) ·
            <code>[<?php echo esc_html( $sel ); ?> source="override"]</code>
            (forces stored override). Drop either onto a test page to compare.
        </p>

        <form method="post">
            <?php wp_nonce_field( 'atp_mkt_edit_' . $sel ); ?>
            <textarea name="atp_mkt_content" rows="24" style="width:100%;font-family:Menlo,Monaco,monospace;font-size:12px"><?php echo esc_textarea( $current ); ?></textarea>
            <p>
                <label style="display:inline-flex;align-items:center;gap:6px;margin-right:14px;font-size:13px">
                    <input type="checkbox" name="atp_mkt_disabled" value="1" <?php checked( $is_disabled ); ?>>
                    Disable override (use template default)
                </label>
                <button type="submit" name="atp_mkt_save" class="button button-primary">Save override</button>
                <?php if ( $is_override || $is_disabled ) : ?>
                    <button type="submit" name="atp_mkt_reset" class="button" style="margin-left:8px"
                            onclick="return confirm('Revert this shortcode to the template default? Clears override + toggle.');">Revert to default</button>
                <?php endif; ?>
            </p>
        </form>
    </div>
    <?php
}
