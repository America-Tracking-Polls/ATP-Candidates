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

    // ── Handle save (template + data + toggle in one form) ───────────────
    if ( isset( $_POST['atp_save_sc'] ) && check_admin_referer( 'atp_demo_save' ) ) {
        $tag      = sanitize_key( $_POST['atp_sc_tag'] ?? '' );
        $content  = wp_unslash( $_POST['atp_sc_content'] ?? '' );
        $data     = wp_unslash( $_POST['atp_sc_data']    ?? '' );
        $disabled = ! empty( $_POST['atp_sc_disabled'] );
        if ( $tag ) {
            update_option( 'atp_sc_' . $tag, $content, false );
            // Validate JSON data patch — empty / valid OK; invalid JSON is a saved warning
            $data_msg = '';
            if ( $data === '' ) {
                delete_option( 'atp_sc_' . $tag . '_data' );
            } else {
                $decoded = json_decode( $data, true );
                if ( $decoded === null && json_last_error() !== JSON_ERROR_NONE ) {
                    $data_msg = ' <strong style="color:#cf222e">Data patch ignored — invalid JSON: ' . esc_html( json_last_error_msg() ) . '</strong>';
                } else {
                    update_option( 'atp_sc_' . $tag . '_data', $data, false );
                }
            }
            if ( $disabled ) {
                update_option( 'atp_sc_' . $tag . '_disabled', 1, false );
            } else {
                delete_option( 'atp_sc_' . $tag . '_disabled' );
            }
            echo '<div class="notice notice-success is-dismissible"><p>Shortcode <code>[' . esc_html( $tag ) . ']</code> saved.' . $data_msg . '</p></div>';
        }
    }

    // ── Handle reset ───────────────────────────────────────────────────────────
    if ( isset( $_POST['atp_reset_sc'] ) && check_admin_referer( 'atp_demo_save' ) ) {
        $tag = sanitize_key( $_POST['atp_sc_tag'] ?? '' );
        if ( $tag ) {
            delete_option( 'atp_sc_' . $tag );
            delete_option( 'atp_sc_' . $tag . '_data' );
            delete_option( 'atp_sc_' . $tag . '_disabled' );
            echo '<div class="notice notice-success is-dismissible"><p>Shortcode <code>[' . esc_html( $tag ) . ']</code> reset to default (template, data, and toggle cleared).</p></div>';
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
                $stored       = get_option( 'atp_sc_' . $sc['tag'] );
                $stored_data  = get_option( 'atp_sc_' . $sc['tag'] . '_data', '' );
                $is_disabled  = (bool) get_option( 'atp_sc_' . $sc['tag'] . '_disabled', false );
                $is_modified  = $stored !== false && $stored !== '';
                $has_data     = is_string( $stored_data ) && $stored_data !== '';
                $display      = $is_modified ? $stored : $sc['default'];
            ?>
            <div class="atp-sc-card" id="sc-<?php echo esc_attr( $sc['tag'] ); ?>">

                <div class="atp-sc-card-header">
                    <div class="atp-sc-card-meta">
                        <span class="atp-sc-label"><?php echo esc_html( $sc['label'] ); ?></span>
                        <?php if ( $is_modified && ! $is_disabled ) : ?><span class="atp-badge-modified" style="background:#fff3cd;color:#856404;border:1px solid #ffeeba;padding:2px 8px;border-radius:3px;font-size:11px;margin-left:8px">Override active</span><?php endif; ?>
                        <?php if ( $is_modified && $is_disabled ) : ?><span style="background:#e2e3e5;color:#41464b;border:1px solid #d3d6d8;padding:2px 8px;border-radius:3px;font-size:11px;margin-left:8px">Override stored, disabled</span><?php endif; ?>
                        <?php if ( $has_data ) : ?><span style="background:#cfe2ff;color:#084298;border:1px solid #b6d4fe;padding:2px 8px;border-radius:3px;font-size:11px;margin-left:8px">Data patch</span><?php endif; ?>
                    </div>
                    <p class="atp-sc-desc"><?php echo esc_html( $sc['desc'] ); ?></p>
                    <div class="atp-sc-tag-row">
                        <code class="atp-sc-tag atp-copy-btn"
                              data-copy="[<?php echo esc_attr( $sc['tag'] ); ?>]"
                              title="Click to copy">[<?php echo esc_html( $sc['tag'] ); ?>]</code>
                        <span class="atp-tag-hint">↑ click to copy</span>
                        <span style="margin-left:auto;font-size:11px;color:#666">Preview:
                            <code class="atp-copy-btn" data-copy="[<?php echo esc_attr( $sc['tag'] ); ?> source=&quot;core&quot;]" style="cursor:pointer">core</code>
                            ·
                            <code class="atp-copy-btn" data-copy="[<?php echo esc_attr( $sc['tag'] ); ?> source=&quot;override&quot;]" style="cursor:pointer">override</code>
                        </span>
                    </div>
                </div>

                <form method="post" class="atp-sc-form">
                    <?php wp_nonce_field( 'atp_demo_save' ); ?>
                    <input type="hidden" name="atp_sc_tag" value="<?php echo esc_attr( $sc['tag'] ); ?>">

                    <div class="atp-textarea-wrap">
                        <div class="atp-textarea-toolbar">
                            <span class="atp-toolbar-hint">HTML template · {{tokens}} pull from V3 JSON</span>
                            <div class="atp-toolbar-btns">
                                <label style="display:inline-flex;align-items:center;gap:6px;font-size:12px;margin-right:8px">
                                    <input type="checkbox" name="atp_sc_disabled" value="1" <?php checked( $is_disabled ); ?>>
                                    Disable override (use core default)
                                </label>
                                <button type="button" class="atp-copy-code-btn button">⎘ Copy Code</button>
                                <button type="submit" name="atp_save_sc" class="button button-primary">Save</button>
                                <?php if ( $is_modified || $has_data || $is_disabled ) : ?>
                                <button type="submit" name="atp_reset_sc" class="button atp-reset-btn"
                                    onclick="return confirm('Reset [<?php echo esc_js( $sc['tag'] ); ?>] to its default? Clears template override, data patch, and disable toggle.')">↺ Reset all</button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <textarea name="atp_sc_content" class="atp-sc-textarea" rows="14"
                                  spellcheck="false"><?php echo esc_textarea( $display ); ?></textarea>
                    </div>

                    <details style="margin-top:8px;padding:8px 10px;background:#f6f7f9;border:1px solid #e0e0e0;border-radius:4px">
                        <summary style="cursor:pointer;font-size:12px;font-weight:600;color:#555">Data patch (optional JSON) — overrides specific {{tokens}} for this shortcode only</summary>
                        <p style="font-size:11px;color:#666;margin:8px 0 6px">
                            Paste a JSON object like <code>{"display_name":"Sarah J. Chen","tagline":"For District 5"}</code>.
                            Keys you specify here win over the V3 JSON; anything you leave out falls through to the V3 source of truth.
                            Leave empty to use V3 JSON only.
                        </p>
                        <textarea name="atp_sc_data" rows="6" style="width:100%;font-family:Menlo,Monaco,monospace;font-size:11px"
                                  spellcheck="false"><?php echo esc_textarea( $stored_data ); ?></textarea>
                    </details>
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
