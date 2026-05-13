<?php
/**
 * Candidate Page Engine — connects intake form output to the page template.
 *
 * How it works:
 * 1. Admin selects an "active candidate" (saved as WP option) or
 *    imports a JSON file directly via the admin UI.
 * 2. When any atp_cand_* shortcode renders, the engine replaces
 *    {{field_id}} tokens with real candidate data.
 * 3. Array values (checkboxes) are joined with commas.
 * 4. Empty tokens are replaced with empty strings (no leftover {{braces}}).
 *
 * The JSON can come from:
 * - An existing intake form submission (atp_candidate CPT post)
 * - A pasted/uploaded JSON blob (stored in WP options)
 * - The example-intake.json shipped with the plugin
 *
 * @package ATPDemo
 * @since   2.0.1
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* ─────────────────────────────────────────────────────────────────────────────
   Option keys
   ───────────────────────────────────────────────────────────────────────── */
define( 'ATP_CAND_SOURCE',  'atp_cand_source' );   // 'post' | 'json'
define( 'ATP_CAND_POST_ID', 'atp_cand_post_id' );  // int — CPT post ID
define( 'ATP_CAND_JSON',    'atp_cand_json' );      // string — raw JSON blob

/* ─────────────────────────────────────────────────────────────────────────────
   Token replacement — the core engine
   ───────────────────────────────────────────────────────────────────────── */

/**
 * Get the active candidate's data as a flat key=>value array.
 * Returns empty array if nothing configured.
 */
function atp_cand_get_data() {
    static $cache = null;
    if ( $cache !== null ) return $cache;

    $source = get_option( ATP_CAND_SOURCE, '' );

    if ( $source === 'post' ) {
        $pid = (int) get_option( ATP_CAND_POST_ID, 0 );
        if ( $pid && get_post_type( $pid ) === 'atp_candidate' ) {
            $raw = get_post_meta( $pid );
            $data = [];
            foreach ( $raw as $k => $v ) {
                if ( str_starts_with( $k, '_' ) ) continue; // skip WP internals
                $val = maybe_unserialize( $v[0] );
                $data[ $k ] = is_array( $val ) ? implode( ', ', $val ) : (string) $val;
            }
            $cache = $data;
            return $cache;
        }
    }

    if ( $source === 'json' ) {
        $json_str = get_option( ATP_CAND_JSON, '' );
        if ( $json_str ) {
            $parsed = json_decode( $json_str, true );
            if ( is_array( $parsed ) ) {
                $data = [];
                foreach ( $parsed as $k => $v ) {
                    if ( str_starts_with( $k, '_' ) ) continue; // skip meta keys
                    if ( is_array( $v ) ) {
                        $data[ $k ] = implode( ', ', $v );
                    } else {
                        $data[ $k ] = (string) $v;
                    }
                }
                $cache = $data;
                return $cache;
            }
        }
    }

    $cache = [];
    return $cache;
}

/**
 * Replace all {{token}} placeholders in HTML with candidate data.
 * Tokens that don't match any key are replaced with empty string.
 *
 * @param string $html   Template HTML with {{token}} placeholders.
 * @param array  $patch  Optional per-shortcode data patch (from
 *                       atp_demo_get_data_patch). Patch keys win
 *                       over V3 JSON keys with the same name.
 */
function atp_cand_replace_tokens( $html, $patch = [] ) {
    $data = atp_cand_get_data();
    if ( ! empty( $patch ) && is_array( $patch ) ) {
        $data = array_merge( $data, $patch );
    }
    if ( empty( $data ) ) {
        return preg_replace( '/\{\{[a-z_]+\}\}/', '', $html );
    }

    foreach ( $data as $key => $value ) {
        if ( ! is_scalar( $value ) ) continue;
        $html = str_replace( '{{' . $key . '}}', esc_html( $value ), $html );
    }

    // Clean up any remaining unreplaced tokens
    $html = preg_replace( '/\{\{[a-z_]+\}\}/', '', $html );

    return $html;
}

/* ─────────────────────────────────────────────────────────────────────────────
   Dynamic PHP renderers for structured sections
   These generate the correct number of cards from intake data.
   ───────────────────────────────────────────────────────────────────────── */

/**
 * Render issues section — parses issue_categories (array) and issue_positions
 * (structured text with "Category: description" format) into individual cards.
 */
function atp_cand_render_issues( $atts = [] ) {
    $data = atp_cand_get_data();

    // Parse issue_positions into structured array
    // Expected format: "Category Name: Position text...\n\nCategory Name: Position text..."
    $cards = [];
    if ( ! empty( $data['issue_positions'] ) ) {
        // Split on double newline or on lines that start with a category name followed by colon
        $raw = $data['issue_positions'];
        $chunks = preg_split( '/\n\n+/', $raw );
        foreach ( $chunks as $chunk ) {
            $chunk = trim( $chunk );
            if ( empty( $chunk ) ) continue;
            // Try to split "Category: description"
            if ( preg_match( '/^([^:]+):\s*(.+)$/s', $chunk, $m ) ) {
                $cards[] = [
                    'name' => trim( $m[1] ),
                    'desc' => trim( $m[2] ),
                ];
            } else {
                // No colon format — use as-is with generic label
                $cards[] = [
                    'name' => 'Policy Position',
                    'desc' => $chunk,
                ];
            }
        }
    }

    // Fallback: if no positions parsed but we have categories, show categories as empty cards
    if ( empty( $cards ) && ! empty( $data['issue_categories'] ) ) {
        $cats = is_array( $data['issue_categories'] )
            ? $data['issue_categories']
            : array_map( 'trim', explode( ',', $data['issue_categories'] ) );
        foreach ( $cats as $cat ) {
            $cards[] = [ 'name' => $cat, 'desc' => '' ];
        }
    }

    // If still nothing, return the default template HTML with token replacement
    if ( empty( $cards ) ) {
        $html = atp_demo_get_default( 'atp_cand_issues' );
        return atp_cand_replace_tokens( $html );
    }

    $differentiator = esc_html( $data['differentiator'] ?? '' );

    $out  = '<section class="cand-section cand-section-cream" id="issues">' . "\n";
    $out .= '  <div class="cand-container">' . "\n";
    $out .= '    <div class="cand-section-label">Where I Stand</div>' . "\n";
    $out .= '    <h2 class="cand-section-title">Key Issues</h2>' . "\n";
    if ( $differentiator ) {
        $out .= '    <p class="cand-section-subtitle">' . $differentiator . '</p>' . "\n";
    }
    $out .= '    <div class="cand-issues-grid">' . "\n";

    foreach ( $cards as $i => $card ) {
        $out .= '      <div class="cand-issue-card">' . "\n";
        $out .= '        <div class="cand-issue-tag">Priority Issue</div>' . "\n";
        $out .= '        <h3 class="cand-issue-name">' . esc_html( $card['name'] ) . '</h3>' . "\n";
        if ( $card['desc'] ) {
            $out .= '        <p class="cand-issue-desc">' . esc_html( $card['desc'] ) . '</p>' . "\n";
        }
        $out .= '      </div>' . "\n";
    }

    $out .= '    </div>' . "\n";
    $out .= '  </div>' . "\n";
    $out .= '</section>';

    return $out;
}

/**
 * Render endorsements section — parses the endorsements textarea into
 * individual quote cards. Expected format per line:
 *   Name — 'Quote text'
 *   Name, Title — 'Quote text'
 *   Organization name (no quote)
 */
function atp_cand_render_endorsements( $atts = [] ) {
    $data = atp_cand_get_data();

    if ( empty( $data['endorsements'] ) ) {
        // No data — return default template
        $html = atp_demo_get_default( 'atp_cand_endorsements' );
        return atp_cand_replace_tokens( $html );
    }

    $lines = preg_split( '/\n+/', trim( $data['endorsements'] ) );
    $items = [];

    foreach ( $lines as $line ) {
        $line = trim( $line );
        if ( empty( $line ) ) continue;

        $name  = $line;
        $quote = '';
        $role  = '';

        // Try to extract quote: Name — 'Quote' or Name — "Quote"
        if ( preg_match( '/^(.+?)\s*[—–-]\s*[\'"](.+?)[\'"](.*)$/u', $line, $m ) ) {
            $name  = trim( $m[1] );
            $quote = trim( $m[2] );
        } elseif ( preg_match( '/^(.+?)\s*[—–-]\s*(.+)$/u', $line, $m ) ) {
            // Name — Description (no quotes)
            $name = trim( $m[1] );
            $quote = trim( $m[2] );
        }

        // Try to split name from role: "Name, Title" or "Name (Title)"
        if ( preg_match( '/^(.+?),\s*(.+)$/', $name, $m2 ) ) {
            $name = trim( $m2[1] );
            $role = trim( $m2[2] );
        }

        $items[] = [ 'name' => $name, 'role' => $role, 'quote' => $quote ];
    }

    if ( empty( $items ) ) {
        $html = atp_demo_get_default( 'atp_cand_endorsements' );
        return atp_cand_replace_tokens( $html );
    }

    $out  = '<section class="cand-section cand-section-light" id="endorsements">' . "\n";
    $out .= '  <div class="cand-container">' . "\n";
    $out .= '    <div class="cand-section-label">Endorsements</div>' . "\n";
    $out .= '    <h2 class="cand-section-title">Trusted by Leaders</h2>' . "\n";
    $out .= '    <div class="cand-endorsements-grid">' . "\n";

    foreach ( $items as $item ) {
        $out .= '      <div class="cand-endorsement">' . "\n";
        if ( $item['quote'] ) {
            $out .= '        <p class="cand-endorsement-quote">' . esc_html( $item['quote'] ) . '</p>' . "\n";
        }
        $out .= '        <div class="cand-endorsement-name">' . esc_html( $item['name'] ) . '</div>' . "\n";
        if ( $item['role'] ) {
            $out .= '        <div class="cand-endorsement-role">' . esc_html( $item['role'] ) . '</div>' . "\n";
        }
        $out .= '      </div>' . "\n";
    }

    $out .= '    </div>' . "\n";
    $out .= '  </div>' . "\n";
    $out .= '</section>';

    return $out;
}

/**
 * Render social media section — only shows links that have URLs.
 * Skips empty social profiles instead of rendering dead links.
 */
function atp_cand_render_social( $atts = [] ) {
    $data = atp_cand_get_data();

    $platforms = [
        'facebook'     => 'Facebook',
        'twitter_x'    => 'X / Twitter',
        'instagram'    => 'Instagram',
        'youtube'      => 'YouTube',
        'tiktok'       => 'TikTok',
        'linkedin'     => 'LinkedIn',
        'social_other' => 'Other',
    ];

    $links = [];
    foreach ( $platforms as $key => $label ) {
        if ( ! empty( $data[ $key ] ) ) {
            $links[] = [ 'url' => $data[ $key ], 'label' => $label ];
        }
    }

    if ( empty( $links ) ) {
        // No social data — return default template with token replacement
        $html = atp_demo_get_default( 'atp_cand_social' );
        return atp_cand_replace_tokens( $html );
    }

    $out  = '<section class="cand-section cand-section-cream" id="connect">' . "\n";
    $out .= '  <div class="cand-container">' . "\n";
    $out .= '    <div class="cand-section-label">Stay Connected</div>' . "\n";
    $out .= '    <h2 class="cand-section-title">Follow the Campaign</h2>' . "\n";
    $out .= '    <p class="cand-section-subtitle">Stay up to date on events, policy updates, and ways to get involved.</p>' . "\n";
    $out .= '    <div class="cand-social">' . "\n";

    foreach ( $links as $link ) {
        $out .= '      <a href="' . esc_url( $link['url'] ) . '" class="cand-social-link" target="_blank" rel="noopener">' . esc_html( $link['label'] ) . '</a>' . "\n";
    }

    $out .= '    </div>' . "\n";
    $out .= '  </div>' . "\n";
    $out .= '</section>';

    return $out;
}

/* ─────────────────────────────────────────────────────────────────────────────
   Admin page — Candidate Page Settings
   ───────────────────────────────────────────────────────────────────────── */

add_action( 'admin_menu', 'atp_cand_admin_menu' );

function atp_cand_admin_menu() {
    add_submenu_page(
        'atp-demo-shortcodes',
        'Candidate Page Settings',
        'Candidate Page',
        'manage_options',
        'atp-candidate-page',
        'atp_cand_admin_render'
    );
}

function atp_cand_admin_render() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    $notice = '';

    // ── Handle Page JSON import ──────────────────────────────────────────────
    if ( isset( $_POST['atp_cand_import_page'] ) && check_admin_referer( 'atp_cand_settings' ) ) {
        $raw    = wp_unslash( $_POST['atp_cand_page_json'] ?? '' );
        $parsed = json_decode( $raw, true );

        if ( is_array( $parsed ) ) {
            $imported = 0;
            $tags     = [];
            foreach ( $parsed as $key => $html ) {
                if ( str_starts_with( $key, '_' ) ) continue; // skip metadata keys
                if ( $key === 'atp_cand_styles' ) continue;   // don't overwrite styles
                if ( ! is_string( $html ) ) continue;

                update_option( 'atp_sc_' . $key, $html, false );
                $imported++;
                $tags[] = $key;
            }
            $cand = $parsed['_candidate'] ?? 'Unknown candidate';
            $notice = '<div class="notice notice-success is-dismissible"><p><strong>' . $imported . ' shortcodes imported</strong> for ' . esc_html( $cand ) . ':<br><code>' . esc_html( implode( '</code> <code>', $tags ) ) . '</code></p></div>';
        } else {
            $notice = '<div class="notice notice-error is-dismissible"><p>Invalid JSON. Make sure you paste the AI-generated Page JSON, not the intake form JSON.</p></div>';
        }
    }

    // ── Handle load example page JSON ────────────────────────────────────────
    if ( isset( $_POST['atp_cand_load_example_page'] ) && check_admin_referer( 'atp_cand_settings' ) ) {
        $path = ATP_DEMO_DIR . 'example-page.json';
        if ( file_exists( $path ) ) {
            $parsed = json_decode( file_get_contents( $path ), true );
            if ( is_array( $parsed ) ) {
                $imported = 0;
                foreach ( $parsed as $key => $html ) {
                    if ( str_starts_with( $key, '_' ) || $key === 'atp_cand_styles' || ! is_string( $html ) ) continue;
                    update_option( 'atp_sc_' . $key, $html, false );
                    $imported++;
                }
                $notice = '<div class="notice notice-success is-dismissible"><p><strong>' . $imported . ' shortcodes imported</strong> from example Page JSON (John Stacy for County Commissioner).</p></div>';
            }
        }
    }

    // ── Handle reset all candidate shortcodes ────────────────────────────────
    if ( isset( $_POST['atp_cand_reset_all'] ) && check_admin_referer( 'atp_cand_settings' ) ) {
        $cand_tags = [ 'atp_cand_nav', 'atp_cand_hero', 'atp_cand_about', 'atp_cand_issues',
                       'atp_cand_endorsements', 'atp_cand_donate', 'atp_cand_social', 'atp_cand_footer' ];
        foreach ( $cand_tags as $tag ) {
            delete_option( 'atp_sc_' . $tag );
        }
        // Also clear intake data source
        delete_option( ATP_CAND_SOURCE );
        delete_option( ATP_CAND_POST_ID );
        delete_option( ATP_CAND_JSON );
        $notice = '<div class="notice notice-success is-dismissible"><p>All candidate shortcodes reset to defaults. Page shows placeholder template.</p></div>';
    }

    // ── Handle intake data save (for fallback/PHP renderers) ─────────────────
    if ( isset( $_POST['atp_cand_save'] ) && check_admin_referer( 'atp_cand_settings' ) ) {
        $src = sanitize_key( $_POST['atp_cand_source_type'] ?? '' );

        if ( $src === 'post' ) {
            $pid = (int) ( $_POST['atp_cand_post'] ?? 0 );
            update_option( ATP_CAND_SOURCE, 'post' );
            update_option( ATP_CAND_POST_ID, $pid );
            $name = get_the_title( $pid ) ?: 'Candidate #' . $pid;
            $notice = '<div class="notice notice-success is-dismissible"><p>Intake data linked to <strong>' . esc_html( $name ) . '</strong> (Post #' . $pid . ').</p></div>';
        }

        if ( $src === 'json' ) {
            $raw = wp_unslash( $_POST['atp_cand_json_input'] ?? '' );
            $parsed = json_decode( $raw, true );
            if ( is_array( $parsed ) ) {
                update_option( ATP_CAND_SOURCE, 'json' );
                update_option( ATP_CAND_JSON, $raw, false );
                $name = $parsed['display_name'] ?? $parsed['legal_name'] ?? 'Unknown';
                $notice = '<div class="notice notice-success is-dismissible"><p>Intake data loaded for <strong>' . esc_html( $name ) . '</strong>.</p></div>';
            } else {
                $notice = '<div class="notice notice-error is-dismissible"><p>Invalid JSON.</p></div>';
            }
        }
    }

    // Current state
    $cand_tags = [ 'atp_cand_nav', 'atp_cand_hero', 'atp_cand_about', 'atp_cand_issues',
                   'atp_cand_endorsements', 'atp_cand_donate', 'atp_cand_social', 'atp_cand_footer' ];
    $imported_count = 0;
    foreach ( $cand_tags as $tag ) {
        if ( get_option( 'atp_sc_' . $tag ) !== false ) $imported_count++;
    }
    $has_page_json = $imported_count > 0;

    $current_source = get_option( ATP_CAND_SOURCE, '' );
    $current_post   = (int) get_option( ATP_CAND_POST_ID, 0 );
    $current_json   = get_option( ATP_CAND_JSON, '' );
    $current_data   = atp_cand_get_data();
    $current_name   = $current_data['display_name'] ?? $current_data['legal_name'] ?? '';

    $candidates = get_posts( [ 'post_type' => 'atp_candidate', 'posts_per_page' => -1, 'post_status' => 'publish' ] );
    ?>
    <div class="wrap">
        <div style="display:flex;align-items:center;gap:14px;background:#0e1235;padding:18px 28px;border-radius:6px 6px 0 0;margin:-1px -1px 0">
            <img src="<?php echo esc_url( ATP_DEMO_URL . 'assets/images/ATP-Logo-Red-White.png' ); ?>" alt="ATP" style="height:36px">
            <div>
                <h1 style="margin:0;font-size:22px;color:#fff">Candidate Page</h1>
                <p style="margin:4px 0 0;color:rgba(255,255,255,.65);font-size:13px">
                    Import AI-generated Page JSON to populate the <code style="background:rgba(255,255,255,.1);color:#d42b2b;padding:1px 6px;border-radius:3px">[atp_cand_*]</code> shortcodes.
                </p>
            </div>
        </div>

        <div style="background:#fff;border:1px solid #ddd;border-top:none;border-radius:0 0 6px 6px;padding:28px 32px">
            <?php echo $notice; // phpcs:ignore ?>

            <!-- ── Status ── -->
            <?php if ( $has_page_json ) : ?>
            <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:6px;padding:16px 20px;margin-bottom:24px;display:flex;align-items:center;gap:12px">
                <span style="background:#16a34a;color:#fff;width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0">&#10003;</span>
                <div>
                    <strong style="color:#166534"><?php echo $imported_count; ?>/<?php echo count( $cand_tags ); ?> shortcodes have custom content</strong>
                    <span style="color:#555;font-size:13px"> &mdash; Page JSON is loaded. Edit individual shortcodes in the <a href="<?php echo esc_url( admin_url( 'admin.php?page=atp-demo-shortcodes' ) ); ?>">Shortcode Editor</a>.</span>
                </div>
            </div>
            <?php endif; ?>

            <!-- ══════════════════════════════════════════════════════════════
                 STEP 1: PAGE JSON (the main workflow)
            ═══════════════════════════════════════════════════════════════ -->
            <h2 style="font-size:16px;color:#0e1235;margin:0 0 6px;padding-bottom:8px;border-bottom:2px solid #d42b2b">Step 1 — Import Page JSON</h2>
            <p style="color:#666;font-size:13px;margin:0 0 16px">
                This is the primary workflow. Take the candidate's intake JSON, run it through the
                <strong>PROMPT-TEMPLATE.md</strong> prompt with any AI, then paste the generated Page JSON below.
                Each shortcode gets its own custom HTML &mdash; you control sections, content, layout, everything.
            </p>

            <form method="post" style="margin-bottom:32px">
                <?php wp_nonce_field( 'atp_cand_settings' ); ?>

                <div style="background:#f9f9f9;border:1px solid #e5e5e5;border-radius:6px;padding:20px;margin-bottom:16px">
                    <label style="font-weight:600;font-size:14px;margin-bottom:10px;display:block">
                        Paste AI-generated Page JSON
                    </label>
                    <p style="color:#666;font-size:12px;margin:0 0 10px">
                        The Page JSON maps shortcode tags to final HTML. Keys like <code>atp_cand_hero</code>, <code>atp_cand_issues</code>, etc.
                        Each value is the complete HTML for that section. See <code>example-page.json</code> for the format.
                    </p>
                    <textarea name="atp_cand_page_json" rows="14"
                        style="width:100%;font-family:monospace;font-size:12px;background:#0e1235;color:#7eb8f7;padding:14px;border:1px solid #ddd;border-radius:4px"
                        placeholder='{"atp_cand_nav": "<nav>...</nav>", "atp_cand_hero": "<section>...</section>", ...}'></textarea>
                </div>

                <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                    <button type="submit" name="atp_cand_import_page" class="button button-primary button-hero" style="font-size:14px">
                        Import Page JSON
                    </button>
                    <button type="submit" name="atp_cand_load_example_page" class="button" style="font-size:13px">
                        Load Example (John Stacy)
                    </button>
                    <?php if ( $has_page_json ) : ?>
                    <button type="submit" name="atp_cand_reset_all" class="button" style="font-size:13px;color:#b00;border-color:#b00"
                        onclick="return confirm('Reset ALL candidate shortcodes to defaults? This removes all imported content.')">
                        Reset All to Defaults
                    </button>
                    <?php endif; ?>
                </div>
            </form>

            <!-- ══════════════════════════════════════════════════════════════
                 STEP 2: INTAKE DATA (fallback for token replacement)
            ═══════════════════════════════════════════════════════════════ -->
            <h2 style="font-size:16px;color:#0e1235;margin:0 0 6px;padding-bottom:8px;border-bottom:2px solid #d42b2b">Step 2 — Intake Data Source (optional fallback)</h2>
            <p style="color:#666;font-size:13px;margin:0 0 16px">
                If you haven't imported Page JSON yet, the shortcodes can fall back to auto-rendering
                from raw intake data using <code>{{token}}</code> replacement. This is the quick path &mdash;
                less control but instant results.
            </p>

            <form method="post" style="margin-bottom:32px">
                <?php wp_nonce_field( 'atp_cand_settings' ); ?>

                <div style="background:#f9f9f9;border:1px solid #e5e5e5;border-radius:6px;padding:20px;margin-bottom:16px">
                    <label style="display:flex;align-items:center;gap:10px;font-weight:600;font-size:14px;margin-bottom:12px;cursor:pointer">
                        <input type="radio" name="atp_cand_source_type" value="post"
                            <?php checked( $current_source, 'post' ); ?>>
                        Use an intake form submission
                    </label>
                    <?php if ( empty( $candidates ) ) : ?>
                        <p style="color:#888;font-size:13px;margin-left:26px">No intake submissions yet.</p>
                    <?php else : ?>
                        <select name="atp_cand_post" style="margin-left:26px;min-width:300px">
                            <?php foreach ( $candidates as $c ) :
                                $m = get_post_meta( $c->ID );
                                $cname = $m['display_name'][0] ?? $m['legal_name'][0] ?? $c->post_title;
                                $coffice = $m['office'][0] ?? '';
                            ?>
                            <option value="<?php echo esc_attr( $c->ID ); ?>" <?php selected( $current_post, $c->ID ); ?>>
                                <?php echo esc_html( $cname ); if ( $coffice ) echo ' — ' . esc_html( $coffice ); ?> (ID <?php echo $c->ID; ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>

                <div style="background:#f9f9f9;border:1px solid #e5e5e5;border-radius:6px;padding:20px;margin-bottom:16px">
                    <label style="display:flex;align-items:center;gap:10px;font-weight:600;font-size:14px;margin-bottom:12px;cursor:pointer">
                        <input type="radio" name="atp_cand_source_type" value="json"
                            <?php checked( $current_source, 'json' ); ?>>
                        Paste intake JSON directly
                    </label>
                    <textarea name="atp_cand_json_input" rows="6"
                        style="width:calc(100% - 26px);margin-left:26px;font-family:monospace;font-size:11px;background:#0e1235;color:#7eb8f7;padding:12px;border:1px solid #ddd;border-radius:4px"
                        placeholder="Paste intake form JSON..."><?php
                        if ( $current_source === 'json' && $current_json ) echo esc_textarea( $current_json );
                    ?></textarea>
                </div>

                <button type="submit" name="atp_cand_save" class="button" style="font-size:13px">
                    Save Intake Data
                </button>
            </form>

            <!-- ══════════════════════════════════════════════════════════════
                 WORKFLOW REFERENCE
            ═══════════════════════════════════════════════════════════════ -->
            <h2 style="font-size:16px;color:#0e1235;margin:0 0 6px;padding-bottom:8px;border-bottom:2px solid #d42b2b">Workflow</h2>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px">
                <div style="background:#f9f9f9;border:1px solid #e5e5e5;border-top:3px solid #d42b2b;border-radius:0 0 4px 4px;padding:16px">
                    <div style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#d42b2b;margin-bottom:4px">1. Intake</div>
                    <div style="font-size:13px;color:#333">Candidate fills out the <code>[atp_intake]</code> form. Export the JSON.</div>
                </div>
                <div style="background:#f9f9f9;border:1px solid #e5e5e5;border-top:3px solid #d42b2b;border-radius:0 0 4px 4px;padding:16px">
                    <div style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#d42b2b;margin-bottom:4px">2. Generate</div>
                    <div style="font-size:13px;color:#333">Paste intake JSON into the <strong>PROMPT-TEMPLATE.md</strong> prompt. Run with any AI. Get Page JSON back.</div>
                </div>
                <div style="background:#f9f9f9;border:1px solid #e5e5e5;border-top:3px solid #d42b2b;border-radius:0 0 4px 4px;padding:16px">
                    <div style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#d42b2b;margin-bottom:4px">3. Import</div>
                    <div style="font-size:13px;color:#333">Paste Page JSON above. Click <strong>Import Page JSON</strong>. Shortcodes are now populated.</div>
                </div>
                <div style="background:#f9f9f9;border:1px solid #e5e5e5;border-top:3px solid #d42b2b;border-radius:0 0 4px 4px;padding:16px">
                    <div style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#d42b2b;margin-bottom:4px">4. Customize</div>
                    <div style="font-size:13px;color:#333">Edit any shortcode in the <a href="<?php echo esc_url( admin_url( 'admin.php?page=atp-demo-shortcodes' ) ); ?>">Shortcode Editor</a>. Add/remove sections on the WP page.</div>
                </div>
            </div>

        </div>
    </div>
    <?php
}
