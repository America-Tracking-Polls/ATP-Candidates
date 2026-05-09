<?php
/**
 * Plugin Name: ATP Marketing Site
 * Plugin URI:  https://americatrackingpolls.com
 * Description: Shortcode-based renderer for ATP's marketing pages. The static
 *              HTML in `templates/*.html` is the default content for each
 *              shortcode; WP options override defaults so admins (or AI via
 *              MCP) can edit any section independently. Mirrors the
 *              candidate-platform plugin pattern.
 * Version:     2.0.0
 * Author:      Mirror Factory / America Tracking Polls
 * Text Domain: atp-marketing
 *
 * Architecture
 *   - 13 shortcodes ([atp_mkt_*]), one per logical section
 *   - Each shortcode's default content lives in `templates/<file>`
 *   - An admin can override any shortcode's content via Settings ->
 *     ATP Marketing -> Edit Shortcodes, which writes to wp_options as
 *     `atp_mkt_sc_<tag>`
 *   - On activation: 3 WP pages are created with shortcode markup
 *     (Marketing Home, Brand Guide, Demo Hub)
 *
 * @since 2.0.0  Refactored from static-HTML serve to shortcode library
 */
if ( ! defined( 'ABSPATH' ) ) exit;

const ATP_MKT_VERSION = '2.0.0';

/* ─────────────────────────────────────────────────────────────────────────
   Shortcode registry — tag => [ template, wrapOpen, wrapClose, label, desc ]
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
   Shortcode renderer — pulls from option override or template file default
   ───────────────────────────────────────────────────────────────────────── */

function atp_mkt_render_shortcode( $tag ) {
    $reg = atp_mkt_registry();
    if ( ! isset( $reg[ $tag ] ) ) return '';
    $row    = $reg[ $tag ];
    $file   = $row[0];
    $open   = $row[1];
    $close  = $row[2];

    $stored = get_option( 'atp_mkt_sc_' . $tag, null );
    if ( is_string( $stored ) && $stored !== '' ) {
        $content = $stored;
    } else {
        $path = __DIR__ . '/templates/' . $file;
        $content = is_readable( $path ) ? file_get_contents( $path ) : '';
    }
    return $open . $content . $close;
}

add_action( 'init', 'atp_mkt_register_shortcodes' );
function atp_mkt_register_shortcodes() {
    foreach ( array_keys( atp_mkt_registry() ) as $tag ) {
        add_shortcode( $tag, function() use ( $tag ) {
            return atp_mkt_render_shortcode( $tag );
        } );
    }
}

/* ─────────────────────────────────────────────────────────────────────────
   Activation: create marketing pages with shortcode markup
   ───────────────────────────────────────────────────────────────────────── */

register_activation_hook( __FILE__, 'atp_mkt_activate' );
function atp_mkt_activate() {
    foreach ( atp_mkt_default_pages() as $slug => $info ) {
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

function atp_mkt_default_pages() {
    return [
        'marketing' => [
            'title'      => 'ATP Marketing Home',
            'front_page' => true,
            'content'    => atp_mkt_compose_homepage(),
        ],
        'marketing-brand-guide' => [
            'title'      => 'ATP Brand Guide',
            'front_page' => false,
            'content'    => '[atp_mkt_styles]<p style="padding:40px;font-family:sans-serif">Brand guide is not yet shortcoded — see <code>brand-guide.html</code> for the canonical version.</p>',
        ],
        'marketing-demo-hub' => [
            'title'      => 'ATP Demo Hub',
            'front_page' => false,
            'content'    => '[atp_mkt_styles]<p style="padding:40px;font-family:sans-serif">Demo hub is not yet shortcoded — see <code>index.html</code> for the canonical version.</p>',
        ],
    ];
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
   Admin: shortcode library + per-shortcode editor
   ───────────────────────────────────────────────────────────────────────── */

add_action( 'admin_menu', 'atp_mkt_admin_menu' );
function atp_mkt_admin_menu() {
    add_menu_page( 'ATP Marketing', 'ATP Marketing', 'manage_options', 'atp-mkt', 'atp_mkt_admin_page', 'dashicons-megaphone', 58 );
    add_submenu_page( 'atp-mkt', 'Edit Shortcodes', 'Edit Shortcodes', 'manage_options', 'atp-mkt-edit', 'atp_mkt_admin_edit_page' );
}

function atp_mkt_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) return;
    $reg  = atp_mkt_registry();
    $home = get_page_by_path( 'marketing' );
    ?>
    <div class="wrap" style="max-width:880px">
        <h1>ATP Marketing Site</h1>
        <p>Rendered by 13 shortcodes — one per section. Each shortcode's default content lives in <code>templates/&lt;file&gt;</code>; you can override any of them from the Edit Shortcodes screen.</p>
        <p>
            <?php if ( $home ) : ?>
                <a href="<?php echo esc_url( get_permalink( $home->ID ) ); ?>" target="_blank" class="button button-primary">Open marketing home page</a>
            <?php endif; ?>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=atp-mkt-edit' ) ); ?>" class="button" style="margin-left:8px">Edit shortcodes</a>
        </p>

        <h2>Shortcode library</h2>
        <table class="widefat striped">
            <thead><tr><th style="width:240px">Shortcode</th><th style="width:200px">Section</th><th>Description</th></tr></thead>
            <tbody>
            <?php foreach ( $reg as $tag => $row ) : ?>
                <tr>
                    <td><code>[<?php echo esc_html( $tag ); ?>]</code></td>
                    <td><?php echo esc_html( $row[3] ); ?></td>
                    <td><?php echo esc_html( $row[4] ); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Composing your own pages</h2>
        <p>Drop any combination of shortcodes into a WordPress page. The default home page uses all 13 in canonical order. Build alternate landing pages with subsets — e.g. a one-pitch page that only renders <code>[atp_mkt_styles] [atp_mkt_hero] [atp_mkt_intake] [atp_mkt_footer] [atp_mkt_scripts]</code>.</p>
        <p><strong>Important:</strong> always include <code>[atp_mkt_styles]</code> first and <code>[atp_mkt_scripts]</code> last on any page that uses these. Without them sections render unstyled and animations don't run.</p>
    </div>
    <?php
}

function atp_mkt_admin_edit_page() {
    if ( ! current_user_can( 'manage_options' ) ) return;
    $reg = atp_mkt_registry();
    $sel = isset( $_GET['sc'] ) ? sanitize_key( $_GET['sc'] ) : '';
    if ( ! isset( $reg[ $sel ] ) ) {
        $sel = array_key_first( $reg );
    }

    if ( isset( $_POST['atp_mkt_save'] ) && check_admin_referer( 'atp_mkt_edit_' . $sel ) ) {
        $val = wp_unslash( $_POST['atp_mkt_content'] ?? '' );
        update_option( 'atp_mkt_sc_' . $sel, $val );
        echo '<div class="notice notice-success is-dismissible"><p>Saved.</p></div>';
    }
    if ( isset( $_POST['atp_mkt_reset'] ) && check_admin_referer( 'atp_mkt_edit_' . $sel ) ) {
        delete_option( 'atp_mkt_sc_' . $sel );
        echo '<div class="notice notice-success is-dismissible"><p>Reverted to template default.</p></div>';
    }

    $stored      = get_option( 'atp_mkt_sc_' . $sel, null );
    $is_override = is_string( $stored ) && $stored !== '';
    $current     = $is_override ? $stored : @file_get_contents( __DIR__ . '/templates/' . $reg[ $sel ][0] );
    ?>
    <div class="wrap" style="max-width:1100px">
        <h1>Edit shortcodes</h1>
        <p>Pick a shortcode to edit. Saving writes to a WP option that overrides the template default. "Revert" deletes the override and falls back to <code>templates/<?php echo esc_html( $reg[ $sel ][0] ); ?></code>.</p>

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
                <span style="color:#1a7f37">✓ default</span> — content below comes from <code>templates/<?php echo esc_html( $reg[ $sel ][0] ); ?></code>
            <?php endif; ?>
        </p>

        <form method="post">
            <?php wp_nonce_field( 'atp_mkt_edit_' . $sel ); ?>
            <textarea name="atp_mkt_content" rows="24" style="width:100%;font-family:Menlo,Monaco,monospace;font-size:12px"><?php echo esc_textarea( $current ); ?></textarea>
            <p>
                <button type="submit" name="atp_mkt_save" class="button button-primary">Save override</button>
                <?php if ( $is_override ) : ?>
                    <button type="submit" name="atp_mkt_reset" class="button" style="margin-left:8px"
                            onclick="return confirm('Revert this shortcode to the template default and delete the override?');">Revert to default</button>
                <?php endif; ?>
            </p>
        </form>
    </div>
    <?php
}
