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

function atp_demo_admin_preview_doc( $tag ) {
    $shortcode = '[' . $tag . ']';
    $html      = function_exists( 'shortcode_exists' ) && shortcode_exists( $tag ) ? do_shortcode( $shortcode ) : '';
    if ( $html === '' && function_exists( 'atp_demo_resolve_template' ) ) {
        $html = atp_demo_resolve_template( $tag );
    }
    $html      = str_replace( '{ATP_PLUGIN_URL}', ATP_DEMO_URL, $html );

    return '<!doctype html><html><head><meta charset="utf-8"><base target="_blank"><style>'
        . 'html,body{margin:0;padding:0;background:#fff;color:#111;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;}'
        . 'body{min-height:220px;overflow:auto;}'
        . 'img,video,iframe{max-width:100%;height:auto;}'
        . '</style></head><body>' . $html . '</body></html>';
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
        <div class="atp-editor-intro">
            <div>
                <h2 class="atp-editor-heading">Shortcode Editor</h2>
                <p class="atp-editor-subheading">Edit one shortcode at a time without forking the whole page.</p>
            </div>
            <div class="atp-override-guide" aria-label="Override system summary">
                <div>
                    <strong>Template</strong>
                    <span>Saved HTML overrides the plugin default unless the override is disabled.</span>
                </div>
                <div>
                    <strong>Data</strong>
                    <span>Optional JSON patches replace only matching {{tokens}}; V3 JSON still fills the rest.</span>
                </div>
                <div>
                    <strong>Preview</strong>
                    <span>Use <code>source="core"</code> or <code>source="override"</code> to compare on a test page.</span>
                </div>
            </div>
        </div>

        <?php foreach ( $registry as $group ) : ?>
        <div class="atp-group">
            <h3 class="atp-group-title"><?php echo esc_html( $group['group'] ); ?></h3>

            <?php foreach ( $group['shortcodes'] as $sc ) :
                $stored       = get_option( 'atp_sc_' . $sc['tag'] );
                $stored_data  = get_option( 'atp_sc_' . $sc['tag'] . '_data', '' );
                $is_disabled  = (bool) get_option( 'atp_sc_' . $sc['tag'] . '_disabled', false );
                $is_modified  = function_exists( 'atp_demo_option_exists' )
                    ? atp_demo_option_exists( 'atp_sc_' . $sc['tag'] )
                    : ( $stored !== false && $stored !== '' );
                $has_data     = is_string( $stored_data ) && $stored_data !== '';
                $display      = $is_modified ? $stored : $sc['default'];
                $preview_doc  = atp_demo_admin_preview_doc( $sc['tag'] );
            ?>
            <div class="atp-sc-card" id="sc-<?php echo esc_attr( $sc['tag'] ); ?>">

                <div class="atp-sc-card-header">
                    <div class="atp-sc-card-meta">
                        <span class="atp-sc-label"><?php echo esc_html( $sc['label'] ); ?></span>
                        <?php if ( $is_modified && ! $is_disabled ) : ?><span class="atp-status-badge atp-status-badge--active">Override active</span><?php endif; ?>
                        <?php if ( $is_modified && $is_disabled ) : ?><span class="atp-status-badge atp-status-badge--muted">Override stored, disabled</span><?php endif; ?>
                        <?php if ( $has_data ) : ?><span class="atp-status-badge atp-status-badge--data">Data patch</span><?php endif; ?>
                    </div>
                    <p class="atp-sc-desc"><?php echo esc_html( $sc['desc'] ); ?></p>
                    <div class="atp-sc-tag-row">
                        <code class="atp-sc-tag atp-copy-btn"
                              data-copy="[<?php echo esc_attr( $sc['tag'] ); ?>]"
                              title="Click to copy">[<?php echo esc_html( $sc['tag'] ); ?>]</code>
                        <span class="atp-tag-hint">↑ click to copy</span>
                        <span class="atp-preview-shortcodes">
                            Compare:
                            <code class="atp-copy-btn" data-copy="[<?php echo esc_attr( $sc['tag'] ); ?> source=&quot;core&quot;]">core</code>
                            <code class="atp-copy-btn" data-copy="[<?php echo esc_attr( $sc['tag'] ); ?> source=&quot;override&quot;]">override</code>
                        </span>
                    </div>
                </div>

                <form method="post" class="atp-sc-form">
                    <?php wp_nonce_field( 'atp_demo_save' ); ?>
                    <input type="hidden" name="atp_sc_tag" value="<?php echo esc_attr( $sc['tag'] ); ?>">

                    <div class="atp-editor-grid">
                    <section class="atp-editor-panel atp-template-panel atp-textarea-wrap">
                        <div class="atp-panel-heading">
                            <div>
                                <h4>Template override</h4>
                                <p>HTML, CSS, or JS saved here becomes this site&apos;s version of the shortcode.</p>
                            </div>
                            <label class="atp-toggle-row">
                                <input type="checkbox" name="atp_sc_disabled" value="1" <?php checked( $is_disabled ); ?>>
                                Disable override
                            </label>
                        </div>
                        <div class="atp-textarea-toolbar">
                            <span class="atp-toolbar-hint">HTML template · {{tokens}} pull from V3 JSON</span>
                            <div class="atp-toolbar-btns">
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
                    </section>

                    <section class="atp-editor-panel atp-preview-panel">
                        <div class="atp-panel-heading">
                            <div>
                                <h4>Rendered preview</h4>
                                <p>Shows the currently active shortcode output. Scripts are blocked in this preview.</p>
                            </div>
                        </div>
                        <iframe
                            class="atp-sc-preview-frame"
                            title="Preview of [<?php echo esc_attr( $sc['tag'] ); ?>]"
                            sandbox=""
                            loading="lazy"
                            srcdoc="<?php echo esc_attr( $preview_doc ); ?>"></iframe>
                        <p class="atp-preview-note">CSS or script-only shortcodes may render as a blank frame here; use the preview shortcodes to compare them on a test page.</p>
                        <div class="atp-preview-actions">
                            <button type="button" class="button atp-copy-btn" data-copy="[<?php echo esc_attr( $sc['tag'] ); ?>]">Copy active</button>
                            <button type="button" class="button atp-copy-btn" data-copy="[<?php echo esc_attr( $sc['tag'] ); ?> source=&quot;core&quot;]">Copy core preview</button>
                            <button type="button" class="button atp-copy-btn" data-copy="[<?php echo esc_attr( $sc['tag'] ); ?> source=&quot;override&quot;]">Copy override preview</button>
                        </div>
                    </section>
                    </div>

                    <details class="atp-data-patch">
                        <summary>Data patch (optional JSON) — override specific {{tokens}} for this shortcode only</summary>
                        <p>
                            Paste a JSON object like <code>{"display_name":"Sarah J. Chen","tagline":"For District 5"}</code>.
                            Keys you specify here win over the V3 JSON; anything you leave out falls through to the V3 source of truth.
                            Leave empty to use V3 JSON only.
                        </p>
                        <textarea name="atp_sc_data" rows="6" class="atp-data-textarea"
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
