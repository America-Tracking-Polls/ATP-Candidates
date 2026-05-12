<?php
/**
 * ATP AI Context
 *
 * Surfaces a comprehensive site overview that AI agents (Vibe AI,
 * Claude Code, Cursor, etc.) can read before making edits. The same
 * content is exposed two ways:
 *
 *   1. As a WP page via the [atp_cand_ai_context] shortcode
 *      (dropped on a hidden "AI Start Here" page by the importer)
 *
 *   2. As structured JSON at /wp-json/atp/v1/site-context
 *      (called programmatically by AI tools)
 *
 * Content includes plugin version, active candidate identity, V3 JSON
 * snapshot, every registered shortcode, current override state per
 * shortcode, brand assets, and a primer on the override system + edit
 * patterns. See AI-CONTEXT.md for the user-facing write-up.
 *
 * @package ATP
 * @since   3.6.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* ─────────────────────────────────────────────────────────────────────────
   Structured site context (single source of truth for both page + REST)
   ───────────────────────────────────────────────────────────────────────── */

function atp_get_site_context() {
    $registry = function_exists( 'atp_demo_get_registry' ) ? atp_demo_get_registry() : [];
    $cand     = function_exists( 'atp_cand_get_data' )    ? atp_cand_get_data()    : [];
    $v3       = ! empty( $cand['_v3_json'] ) && is_array( $cand['_v3_json'] ) ? $cand['_v3_json'] : null;

    // Candidate-side shortcodes — collect override / data-patch / disabled state
    $candidate_shortcodes = [];
    foreach ( $registry as $group ) {
        foreach ( $group['shortcodes'] as $sc ) {
            $tag = $sc['tag'];
            $candidate_shortcodes[] = [
                'tag'             => $tag,
                'label'           => $sc['label'] ?? $tag,
                'desc'            => $sc['desc']  ?? '',
                'group'           => $group['group'] ?? '',
                'has_override'    => get_option( 'atp_sc_' . $tag, '' )      !== '',
                'has_data_patch'  => get_option( 'atp_sc_' . $tag . '_data', '' )      !== '',
                'override_disabled' => (bool) get_option( 'atp_sc_' . $tag . '_disabled', false ),
            ];
        }
    }

    // Marketing shortcodes — same idea, atp_mkt_sc_* keys
    $marketing_shortcodes = [];
    if ( function_exists( 'atp_mkt_registry' ) ) {
        foreach ( atp_mkt_registry() as $tag => $row ) {
            $marketing_shortcodes[] = [
                'tag'               => $tag,
                'label'             => $row[3] ?? $tag,
                'desc'              => $row[4] ?? '',
                'has_override'      => get_option( 'atp_mkt_sc_' . $tag, '' )      !== '',
                'override_disabled' => (bool) get_option( 'atp_mkt_sc_' . $tag . '_disabled', false ),
            ];
        }
    }

    // Pages currently on this site (slug + which shortcodes they contain)
    $pages = [];
    $page_query = get_posts( [
        'post_type'      => 'page',
        'post_status'    => [ 'publish', 'draft', 'private' ],
        'posts_per_page' => 100,
    ] );
    foreach ( $page_query as $p ) {
        $tags = [];
        if ( preg_match_all( '/\[(atp_[a-z_]+)/', $p->post_content, $m ) ) {
            $tags = array_values( array_unique( $m[1] ) );
        }
        $pages[] = [
            'id'         => $p->ID,
            'slug'       => $p->post_name,
            'title'      => $p->post_title,
            'status'     => $p->post_status,
            'shortcodes' => $tags,
            'edit_url'   => admin_url( 'post.php?action=edit&post=' . $p->ID ),
        ];
    }

    return [
        'plugin'   => [
            'name'    => 'ATP Campaign Site',
            'version' => defined( 'ATP_DEMO_VERSION' ) ? ATP_DEMO_VERSION : 'unknown',
            'site_url'   => home_url(),
            'admin_url'  => admin_url(),
            'wp_version' => get_bloginfo( 'version' ),
        ],
        'role' => atp_detect_site_role(),
        'candidate' => $v3 ? [
            'display_name' => $cand['display_name'] ?? $cand['legal_name'] ?? '',
            'office'       => $cand['office']       ?? '',
            'state'        => $cand['state']        ?? '',
            'party'        => $cand['party']        ?? '',
            'committee'    => $cand['committee_name'] ?? '',
            'colors'       => [
                'primary'   => $cand['color_primary']   ?? ( $v3['visual_branding']['primary_color'] ?? '' ),
                'secondary' => $cand['color_secondary'] ?? ( $v3['visual_branding']['secondary_color'] ?? '' ),
                'accent'    => $cand['color_accent']    ?? ( $v3['visual_branding']['accent_color']    ?? '' ),
            ],
            'headshot' => $cand['headshot'] ?? ( $v3['visual_branding']['headshot_link'] ?? '' ),
            'logo'     => $cand['logo']     ?? ( $v3['visual_branding']['logo_link']     ?? '' ),
            'v3_json_post_id' => function_exists( 'atp_cand_get_data_post_id' ) ? atp_cand_get_data_post_id() : null,
        ] : null,
        'candidate_shortcodes' => $candidate_shortcodes,
        'marketing_shortcodes' => $marketing_shortcodes,
        'pages'    => $pages,
        'docs'     => [
            'architecture'    => 'packages/atp-plugin-core/ARCHITECTURE.md',
            'override_system' => 'packages/atp-plugin-core/OVERRIDE-SYSTEM.md',
            'master_plan'     => 'MASTER-PLAN.md',
            'agents'          => 'AGENTS.md',
            'edit_log'        => 'EDIT_LOG.md',
            'site_flow'       => 'docs/candidate-site-flow.md',
        ],
        'edit_patterns' => atp_get_edit_patterns(),
    ];
}

/**
 * Heuristic for which kind of site this is.
 */
function atp_detect_site_role() {
    // Candidate site: has atp_candidate posts
    $has_cand = (bool) get_posts( [ 'post_type' => 'atp_candidate', 'posts_per_page' => 1, 'fields' => 'ids' ] );
    // Intake host: has the intake form shortcode placed on a page
    $intake_pages = get_posts( [
        'post_type'      => 'page',
        'posts_per_page' => 1,
        's'              => '[atp_intake]',
        'fields'         => 'ids',
    ] );
    if ( ! empty( $intake_pages ) ) return 'intake-host';
    if ( $has_cand ) return 'candidate-or-intake-host';
    return 'candidate-or-unconfigured';
}

/**
 * Standard edit patterns for AI agents to follow.
 */
function atp_get_edit_patterns() {
    return [
        [
            'goal'    => 'Change a candidate\'s name / bio / colors / any V3 field',
            'do'      => 'Update the atp_candidate post\'s _v3_json post meta. Every shortcode that references that token updates automatically.',
            'dont'    => 'Edit page content directly. Pages contain shortcode markup that pulls from V3 JSON — replacing the markup with hand-typed HTML loses the JSON binding.',
        ],
        [
            'goal'    => 'Change the layout of one section (e.g. hero looks different) without changing content',
            'do'      => 'Save an override HTML to wp_options.atp_sc_<tag>. Keep {{token}} placeholders so JSON content still flows in.',
            'dont'    => 'Edit the page content. Edit the option override instead.',
        ],
        [
            'goal'    => 'Override copy in just one section, keep V3 JSON unchanged elsewhere',
            'do'      => 'Save a JSON object to wp_options.atp_sc_<tag>_data. Only the keys you specify override V3; others fall through.',
            'dont'    => 'Edit V3 JSON if the change should only affect one section — that propagates everywhere.',
        ],
        [
            'goal'    => 'Test the core default vs the site\'s override',
            'do'      => 'On a test page, drop [atp_cand_<tag> source="core"] and [atp_cand_<tag> source="override"] to compare side-by-side.',
            'dont'    => 'Delete the override to test — toggle wp_options.atp_sc_<tag>_disabled instead.',
        ],
        [
            'goal'    => 'Create the standard 9 candidate pages',
            'do'      => 'WP Admin → ATP Demo → Import Pages — click Import on each (Home, Issues, Donate, Contact, About, Privacy, Cookie/TCPA, Sign Up, Brand Guide). The importer creates each page with the right shortcode markup.',
            'dont'    => 'Manually create pages and paste shortcodes — use the importer for correctness.',
        ],
        [
            'goal'    => 'Remove all existing pages before installing ATP pages',
            'do'      => 'Use the WP REST API to delete pages by ID, or trash via the Pages admin screen.',
            'dont'    => 'Touch pages with id 1 (sample page) without explicit confirmation — they may have been intentionally placed.',
        ],
    ];
}

/* ─────────────────────────────────────────────────────────────────────────
   [atp_cand_ai_context] shortcode renderer
   ───────────────────────────────────────────────────────────────────────── */

function atp_cand_render_ai_context( $atts = [] ) {
    $ctx = atp_get_site_context();
    $f   = function( $s ) { return esc_html( (string) $s ); };
    ob_start();
    ?>
    <div class="atp-ai-context" style="font-family:-apple-system,BlinkMacSystemFont,sans-serif;max-width:920px;margin:24px auto;padding:24px;background:#fafbff;border:1px solid #d9e2ee;border-radius:8px;color:#0e1235;line-height:1.55">
      <h1 style="margin:0 0 4px;font-size:22px">AI Context — Start Here</h1>
      <p style="margin:0 0 18px;font-size:13px;color:#555">If you are an AI assistant (Claude / ChatGPT / Cursor / etc.) connected to this WordPress install via MCP, <strong>read this page first</strong>. It tells you what this site is and how to edit it without breaking the override system.</p>

      <h2 style="font-size:15px;margin:18px 0 6px;color:#0e1235">Plugin &amp; site</h2>
      <ul style="margin:0;padding-left:22px;font-size:13px">
        <li>Plugin: <code><?php echo $f( $ctx['plugin']['name'] ); ?></code> v<?php echo $f( $ctx['plugin']['version'] ); ?></li>
        <li>WP version: <?php echo $f( $ctx['plugin']['wp_version'] ); ?></li>
        <li>Site URL: <code><?php echo $f( $ctx['plugin']['site_url'] ); ?></code></li>
        <li>Site role: <strong><?php echo $f( $ctx['role'] ); ?></strong></li>
        <li>Structured context as JSON: <code>GET <?php echo $f( rest_url( 'atp/v1/site-context' ) ); ?></code></li>
      </ul>

      <?php if ( $ctx['candidate'] ) : ?>
      <h2 style="font-size:15px;margin:18px 0 6px;color:#0e1235">Candidate (source of truth: V3 JSON)</h2>
      <ul style="margin:0;padding-left:22px;font-size:13px">
        <li>Name: <strong><?php echo $f( $ctx['candidate']['display_name'] ); ?></strong></li>
        <li>Office: <?php echo $f( $ctx['candidate']['office'] ); ?> · State: <?php echo $f( $ctx['candidate']['state'] ); ?> · Party: <?php echo $f( $ctx['candidate']['party'] ); ?></li>
        <li>Committee: <?php echo $f( $ctx['candidate']['committee'] ); ?></li>
        <li>Colors: primary <code><?php echo $f( $ctx['candidate']['colors']['primary'] ); ?></code> · secondary <code><?php echo $f( $ctx['candidate']['colors']['secondary'] ); ?></code> · accent <code><?php echo $f( $ctx['candidate']['colors']['accent'] ); ?></code></li>
        <?php if ( $ctx['candidate']['headshot'] ) : ?><li>Headshot: <a href="<?php echo esc_url( $ctx['candidate']['headshot'] ); ?>" target="_blank"><?php echo $f( $ctx['candidate']['headshot'] ); ?></a></li><?php endif; ?>
        <?php if ( $ctx['candidate']['logo'] ) : ?><li>Logo: <a href="<?php echo esc_url( $ctx['candidate']['logo'] ); ?>" target="_blank"><?php echo $f( $ctx['candidate']['logo'] ); ?></a></li><?php endif; ?>
      </ul>
      <?php endif; ?>

      <h2 style="font-size:15px;margin:18px 0 6px;color:#0e1235">Pages on this site</h2>
      <ul style="margin:0;padding-left:22px;font-size:13px">
        <?php foreach ( $ctx['pages'] as $p ) : ?>
          <li>
            <strong><?php echo $f( $p['title'] ); ?></strong> (<code><?php echo $f( $p['slug'] ); ?></code>, <?php echo $f( $p['status'] ); ?>) —
            shortcodes: <?php echo $p['shortcodes'] ? '<code>' . esc_html( implode( ' · ', $p['shortcodes'] ) ) . '</code>' : '<em>none</em>'; ?>
          </li>
        <?php endforeach; ?>
      </ul>

      <h2 style="font-size:15px;margin:18px 0 6px;color:#0e1235">Available shortcodes (candidate side)</h2>
      <table style="width:100%;border-collapse:collapse;font-size:12px">
        <thead><tr style="background:#eef1f7"><th style="text-align:left;padding:6px 8px">Tag</th><th style="text-align:left;padding:6px 8px">Section</th><th style="text-align:left;padding:6px 8px">State</th></tr></thead>
        <tbody>
        <?php foreach ( $ctx['candidate_shortcodes'] as $s ) :
          $state = $s['override_disabled']
            ? 'override stored, disabled'
            : ( $s['has_override'] ? ( $s['has_data_patch'] ? 'override + data patch' : 'override active' )
                                  : ( $s['has_data_patch'] ? 'data patch only' : 'default' ) ); ?>
          <tr style="border-bottom:1px solid #eee"><td style="padding:5px 8px"><code>[<?php echo $f( $s['tag'] ); ?>]</code></td><td style="padding:5px 8px"><?php echo $f( $s['label'] ); ?></td><td style="padding:5px 8px;color:#666"><?php echo $f( $state ); ?></td></tr>
        <?php endforeach; ?>
        </tbody>
      </table>

      <?php if ( ! empty( $ctx['marketing_shortcodes'] ) ) : ?>
      <h2 style="font-size:15px;margin:18px 0 6px;color:#0e1235">Available shortcodes (marketing side)</h2>
      <table style="width:100%;border-collapse:collapse;font-size:12px">
        <thead><tr style="background:#eef1f7"><th style="text-align:left;padding:6px 8px">Tag</th><th style="text-align:left;padding:6px 8px">Section</th><th style="text-align:left;padding:6px 8px">State</th></tr></thead>
        <tbody>
        <?php foreach ( $ctx['marketing_shortcodes'] as $s ) :
          $state = $s['override_disabled'] ? 'override stored, disabled' : ( $s['has_override'] ? 'override active' : 'default' ); ?>
          <tr style="border-bottom:1px solid #eee"><td style="padding:5px 8px"><code>[<?php echo $f( $s['tag'] ); ?>]</code></td><td style="padding:5px 8px"><?php echo $f( $s['label'] ); ?></td><td style="padding:5px 8px;color:#666"><?php echo $f( $state ); ?></td></tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>

      <h2 style="font-size:15px;margin:18px 0 6px;color:#0e1235">How to edit this site (decision tree)</h2>
      <ol style="margin:0;padding-left:22px;font-size:13px">
        <?php foreach ( $ctx['edit_patterns'] as $p ) : ?>
          <li style="margin-bottom:8px"><strong><?php echo $f( $p['goal'] ); ?></strong><br>
            <span style="color:#1a7f37">✓ Do:</span> <?php echo $f( $p['do'] ); ?><br>
            <span style="color:#cf222e">✗ Don't:</span> <?php echo $f( $p['dont'] ); ?>
          </li>
        <?php endforeach; ?>
      </ol>

      <h2 style="font-size:15px;margin:18px 0 6px;color:#0e1235">Deeper docs (in the repo)</h2>
      <ul style="margin:0;padding-left:22px;font-size:13px">
        <li><code>packages/atp-plugin-core/ARCHITECTURE.md</code> — system architecture</li>
        <li><code>packages/atp-plugin-core/OVERRIDE-SYSTEM.md</code> — override system in depth</li>
        <li><code>MASTER-PLAN.md</code> — five architecture diagrams</li>
        <li><code>docs/candidate-site-flow.md</code> — end-to-end ATP-side flow</li>
      </ul>

      <p style="margin:24px 0 0;font-size:12px;color:#888"><em>This page is generated on-the-fly by <code>[atp_cand_ai_context]</code>. It always reflects the live state of this site. The same data is at <code>/wp-json/atp/v1/site-context</code> (App Password auth) for programmatic access.</em></p>
    </div>
    <?php
    return ob_get_clean();
}

/* ─────────────────────────────────────────────────────────────────────────
   REST endpoint: /wp-json/atp/v1/site-context
   ───────────────────────────────────────────────────────────────────────── */

add_action( 'rest_api_init', 'atp_register_site_context_rest' );
function atp_register_site_context_rest() {
    register_rest_route( 'atp/v1', '/site-context', [
        'methods'             => 'GET',
        'callback'            => function() {
            return rest_ensure_response( atp_get_site_context() );
        },
        'permission_callback' => function() {
            // Same capability used elsewhere in admin pages
            return current_user_can( 'edit_posts' );
        },
    ] );
}
