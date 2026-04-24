<?php
/**
 * WordPress admin page — ATP Shortcode Manager.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_menu', 'atp_demo_admin_menu' );
function atp_demo_admin_menu() {
    add_menu_page(
        'ATP Demo Shortcodes',
        'ATP Shortcodes',
        'manage_options',
        'atp-demo-shortcodes',
        'atp_demo_admin_page',
        'dashicons-shortcode',
        30
    );
}

add_action( 'admin_enqueue_scripts', 'atp_demo_admin_assets' );
function atp_demo_admin_assets( $hook ) {
    if ( $hook !== 'toplevel_page_atp-demo-shortcodes' ) return;
    wp_enqueue_style( 'atp-demo-admin', ATP_DEMO_URL . 'assets/css/admin.css', [], ATP_DEMO_VERSION );
}

function atp_demo_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    // ── Handle save ────────────────────────────────────────────────────────────
    if ( isset( $_POST['atp_save_sc'] ) && check_admin_referer( 'atp_demo_save' ) ) {
        $tag     = sanitize_key( $_POST['atp_sc_tag'] ?? '' );
        $content = wp_unslash( $_POST['atp_sc_content'] ?? '' );
        if ( $tag ) {
            update_option( 'atp_sc_' . $tag, $content, false );
            echo '<div class="notice notice-success is-dismissible"><p>Shortcode <code>[' . esc_html( $tag ) . ']</code> saved.</p></div>';
        }
    }

    // ── Handle reset ───────────────────────────────────────────────────────────
    if ( isset( $_POST['atp_reset_sc'] ) && check_admin_referer( 'atp_demo_save' ) ) {
        $tag = sanitize_key( $_POST['atp_sc_tag'] ?? '' );
        if ( $tag ) {
            delete_option( 'atp_sc_' . $tag );
            echo '<div class="notice notice-success is-dismissible"><p>Shortcode <code>[' . esc_html( $tag ) . ']</code> reset to default.</p></div>';
        }
    }

    $registry = atp_demo_get_registry();

    // ── Page Setup Definitions ─────────────────────────────────────────────────
    // Three page sets — copy all shortcodes for a full page in one click.
    $page_sets = [
        'Candidate Intake Form' => [
            'desc'  => 'The ATP candidate onboarding form. 16-step intake with three-condition branching (A/B/C). Saves to Candidates admin.',
            'color' => '#2E2D5A',
            'tags'  => [
                '[atp_intake]',
            ],
        ],
        'Candidate Landing Page' => [
            'desc'  => 'Full campaign website with real John Stacy content. 13 sections including voter survey embed.',
            'color' => '#0B1C33',
            'tags'  => [
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
        ],
    ];

    // Collect all tags for global "Copy All"
    $all_tags = [];
    foreach ( $registry as $group ) {
        foreach ( $group['shortcodes'] as $sc ) {
            $all_tags[] = '[' . $sc['tag'] . ']';
        }
    }
    $all_tags[] = '[atp_intake]';
    $all_tags_str = implode( "\n", $all_tags );
    ?>
    <div class="wrap atp-admin-wrap">

        <!-- ── Header ──────────────────────────────────────────────────────── -->
        <div class="atp-admin-header">
            <img src="<?php echo esc_url( ATP_DEMO_URL . 'assets/images/ATP-Logo-Red-White.png' ); ?>" alt="ATP" class="atp-admin-logo">
            <div class="atp-header-text">
                <h1>ATP Demo Shortcodes</h1>
                <p>Three page setups below — copy all tags for a page in one click, then paste onto any WordPress page. Edit any shortcode's HTML in the editor below.</p>
            </div>
            <div class="atp-header-actions">
                <button class="button atp-copy-btn" data-copy="<?php echo esc_attr( $all_tags_str ); ?>">
                    ⎘ Copy All Tags
                </button>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════════════════
             THREE PAGE SETUP BOXES
        ════════════════════════════════════════════════════════════════════ -->
        <div class="atp-page-setups">
            <?php foreach ( $page_sets as $page_name => $page ) :
                $page_tags_str = implode( "\n", $page['tags'] );
            ?>
            <div class="atp-page-box" style="--page-color:<?php echo esc_attr( $page['color'] ); ?>">
                <div class="atp-page-box-header">
                    <div>
                        <strong class="atp-page-box-title"><?php echo esc_html( $page_name ); ?></strong>
                        <p class="atp-page-box-desc"><?php echo esc_html( $page['desc'] ); ?></p>
                    </div>
                    <button class="button button-primary atp-copy-btn atp-page-copy-btn"
                            data-copy="<?php echo esc_attr( $page_tags_str ); ?>">
                        ⎘ Copy All Shortcodes
                    </button>
                </div>
                <div class="atp-page-tags">
                    <?php foreach ( $page['tags'] as $tag ) : ?>
                        <code class="atp-tag-chip atp-copy-btn" data-copy="<?php echo esc_attr( $tag ); ?>"
                              title="Click to copy"><?php echo esc_html( $tag ); ?></code>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- ══════════════════════════════════════════════════════════════════
             SHORTCODE EDITOR — all groups
        ════════════════════════════════════════════════════════════════════ -->
        <h2 class="atp-editor-heading">Shortcode Editor</h2>
        <p class="atp-editor-subheading">Click any tag to copy it. Edit the HTML, save, then paste into AI to refine and paste back.</p>

        <?php foreach ( $registry as $group ) : ?>
        <div class="atp-group">
            <h3 class="atp-group-title"><?php echo esc_html( $group['group'] ); ?></h3>

            <?php foreach ( $group['shortcodes'] as $sc ) :
                $stored      = get_option( 'atp_sc_' . $sc['tag'] );
                $is_modified = $stored !== false;
                $display     = $is_modified ? $stored : $sc['default'];
            ?>
            <div class="atp-sc-card" id="sc-<?php echo esc_attr( $sc['tag'] ); ?>">

                <div class="atp-sc-card-header">
                    <div class="atp-sc-card-meta">
                        <span class="atp-sc-label"><?php echo esc_html( $sc['label'] ); ?></span>
                        <?php if ( $is_modified ) : ?><span class="atp-badge-modified">Modified</span><?php endif; ?>
                    </div>
                    <p class="atp-sc-desc"><?php echo esc_html( $sc['desc'] ); ?></p>
                    <div class="atp-sc-tag-row">
                        <code class="atp-sc-tag atp-copy-btn"
                              data-copy="[<?php echo esc_attr( $sc['tag'] ); ?>]"
                              title="Click to copy">[<?php echo esc_html( $sc['tag'] ); ?>]</code>
                        <span class="atp-tag-hint">↑ click to copy</span>
                    </div>
                </div>

                <form method="post" class="atp-sc-form">
                    <?php wp_nonce_field( 'atp_demo_save' ); ?>
                    <input type="hidden" name="atp_sc_tag" value="<?php echo esc_attr( $sc['tag'] ); ?>">

                    <div class="atp-textarea-wrap">
                        <div class="atp-textarea-toolbar">
                            <span class="atp-toolbar-hint">HTML · Copy → paste into AI → edit → paste back → Save</span>
                            <div class="atp-toolbar-btns">
                                <button type="button" class="atp-copy-code-btn button">⎘ Copy Code</button>
                                <button type="submit" name="atp_save_sc" class="button button-primary">Save</button>
                                <?php if ( $is_modified ) : ?>
                                <button type="submit" name="atp_reset_sc" class="button atp-reset-btn"
                                    onclick="return confirm('Reset [<?php echo esc_js( $sc['tag'] ); ?>] to its default?')">↺ Reset</button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <textarea name="atp_sc_content" class="atp-sc-textarea" rows="14"
                                  spellcheck="false"><?php echo esc_textarea( $display ); ?></textarea>
                    </div>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>

        <!-- ── Intake form note ────────────────────────────────────────────── -->
        <div class="atp-group">
            <h3 class="atp-group-title">Candidate Intake Form</h3>
            <div class="atp-sc-card">
                <div class="atp-sc-card-header">
                    <div class="atp-sc-card-meta">
                        <span class="atp-sc-label">ATP Candidate Intake Form</span>
                    </div>
                    <p class="atp-sc-desc">
                        16-step candidate onboarding form. Saves submissions as posts, sends email notifications.
                    </p>
                    <div class="atp-sc-tag-row">
                        <code class="atp-sc-tag atp-copy-btn" data-copy="[atp_intake]" title="Click to copy">[atp_intake]</code>
                        <span class="atp-tag-hint">↑ click to copy</span>
                    </div>
                </div>
                <div style="padding:16px 20px;background:#f9f7f5;border-top:1px solid #e5e5e5;font-size:13px;color:#555;line-height:1.6">
                    Manage at <a href="<?php echo esc_url( admin_url('admin.php?page=atp-candidates') ); ?>">ATP Candidates</a>.
                    Edit questions, branding, or notifications at
                    <a href="<?php echo esc_url( admin_url('admin.php?page=atp-settings') ); ?>">ATP Candidates → Settings</a>.
                </div>
            </div>
        </div>

    </div><!-- .atp-admin-wrap -->

    <script>
    (function() {
        // Universal copy on click
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.atp-copy-btn');
            if (!btn) return;
            const text = btn.dataset.copy;
            if (!text) return;
            navigator.clipboard.writeText(text).then(function() {
                const orig = btn.textContent;
                btn.textContent = '✓ Copied!';
                btn.style.color = '#00a32a';
                setTimeout(function() { btn.textContent = orig; btn.style.color = ''; }, 1600);
            });
        });

        // Copy textarea code
        document.querySelectorAll('.atp-copy-code-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const ta = this.closest('.atp-textarea-wrap').querySelector('textarea');
                navigator.clipboard.writeText(ta.value).then(function() {
                    btn.textContent = '✓ Copied!';
                    btn.style.color = '#00a32a';
                    setTimeout(function() { btn.textContent = '⎘ Copy Code'; btn.style.color = ''; }, 1600);
                });
            });
        });
    })();
    </script>
    <?php
}
