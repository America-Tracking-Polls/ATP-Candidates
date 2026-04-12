<?php
/**
 * ATP Changelog — admin page that renders CHANGELOG.md.
 *
 * @package ATPDemo
 * @since   2.0.1
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_menu', 'atp_changelog_admin_menu' );

function atp_changelog_admin_menu() {
    add_submenu_page(
        'atp-demo-shortcodes',
        'ATP Changelog',
        'Changelog',
        'manage_options',
        'atp-changelog',
        'atp_changelog_render_page'
    );
}

function atp_changelog_render_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Unauthorized' );
    }

    // Try plugin dir first, then repo root
    $md_path = '';
    $try_paths = [
        ATP_DEMO_DIR . 'CHANGELOG.md',
        dirname( ATP_DEMO_DIR ) . '/CHANGELOG.md',
        ABSPATH . 'CHANGELOG.md',
    ];
    foreach ( $try_paths as $p ) {
        if ( file_exists( $p ) ) {
            $md_path = $p;
            break;
        }
    }

    $raw = $md_path ? file_get_contents( $md_path ) : '';
    ?>
    <div class="wrap atp-changelog-wrap">
        <div class="atp-changelog-header">
            <img src="<?php echo esc_url( ATP_DEMO_URL . 'assets/images/ATP-Logo-Red-White.png' ); ?>"
                 alt="ATP" style="height:36px;margin-right:14px">
            <div>
                <h1 style="margin:0;font-size:22px;color:#fff">Changelog</h1>
                <p style="margin:4px 0 0;color:rgba(255,255,255,.65);font-size:13px">
                    Version <?php echo esc_html( ATP_DEMO_VERSION ); ?> &mdash; All notable changes to the ATP platform.
                </p>
            </div>
        </div>

        <?php if ( empty( $raw ) ) : ?>
            <div class="notice notice-warning" style="margin-top:20px">
                <p>CHANGELOG.md not found. Place it in the plugin directory or repo root.</p>
            </div>
        <?php else : ?>
            <div class="atp-changelog-body">
                <?php echo atp_changelog_render_markdown( $raw ); ?>
            </div>
        <?php endif; ?>
    </div>

    <style>
    .atp-changelog-header {
        display: flex;
        align-items: center;
        background: #0e1235;
        padding: 18px 28px;
        border-radius: 6px 6px 0 0;
        margin: -1px -1px 0;
    }
    .atp-changelog-body {
        background: #fff;
        border: 1px solid #ddd;
        border-top: none;
        border-radius: 0 0 6px 6px;
        padding: 32px 40px 40px;
        max-width: 900px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        font-size: 14px;
        line-height: 1.7;
        color: #333;
    }
    .atp-changelog-body h1 {
        font-size: 26px;
        font-weight: 700;
        color: #0e1235;
        margin: 0 0 8px;
        padding-bottom: 10px;
        border-bottom: 3px solid #d42b2b;
    }
    .atp-changelog-body h2 {
        font-size: 18px;
        font-weight: 700;
        color: #0e1235;
        margin: 32px 0 12px;
        padding: 10px 14px;
        background: #f7f7f7;
        border-left: 4px solid #d42b2b;
        border-radius: 0 4px 4px 0;
    }
    .atp-changelog-body h3 {
        font-size: 14px;
        font-weight: 700;
        color: #d42b2b;
        text-transform: uppercase;
        letter-spacing: .04em;
        margin: 20px 0 8px;
    }
    .atp-changelog-body h4 {
        font-size: 13px;
        font-weight: 600;
        color: #555;
        margin: 16px 0 6px;
    }
    .atp-changelog-body ul {
        margin: 6px 0 12px 20px;
        padding: 0;
    }
    .atp-changelog-body li {
        margin-bottom: 4px;
    }
    .atp-changelog-body code {
        background: #f0f0f0;
        padding: 1px 6px;
        border-radius: 3px;
        font-size: 12px;
        color: #d42b2b;
    }
    .atp-changelog-body hr {
        border: none;
        border-top: 1px solid #e5e5e5;
        margin: 28px 0;
    }
    .atp-changelog-body strong {
        color: #0e1235;
    }
    .atp-changelog-body p {
        margin: 6px 0 10px;
    }
    </style>
    <?php
}

/**
 * Lightweight Markdown-to-HTML converter for the changelog.
 * Handles headings, bold, code, lists, links, and horizontal rules.
 */
function atp_changelog_render_markdown( $md ) {
    $lines  = explode( "\n", $md );
    $html   = '';
    $in_ul  = false;

    foreach ( $lines as $line ) {
        $trimmed = rtrim( $line );

        // Horizontal rule
        if ( preg_match( '/^---+$/', $trimmed ) ) {
            if ( $in_ul ) { $html .= "</ul>\n"; $in_ul = false; }
            $html .= "<hr>\n";
            continue;
        }

        // Headings
        if ( preg_match( '/^(#{1,4})\s+(.*)$/', $trimmed, $m ) ) {
            if ( $in_ul ) { $html .= "</ul>\n"; $in_ul = false; }
            $level = strlen( $m[1] );
            $text  = atp_changelog_inline( $m[2] );
            $html .= "<h{$level}>{$text}</h{$level}>\n";
            continue;
        }

        // Unordered list
        if ( preg_match( '/^(\s*)[-*]\s+(.*)$/', $trimmed, $m ) ) {
            if ( ! $in_ul ) { $html .= "<ul>\n"; $in_ul = true; }
            $html .= '<li>' . atp_changelog_inline( $m[2] ) . "</li>\n";
            continue;
        }

        // Blank line
        if ( $trimmed === '' ) {
            if ( $in_ul ) { $html .= "</ul>\n"; $in_ul = false; }
            continue;
        }

        // Paragraph
        if ( $in_ul ) { $html .= "</ul>\n"; $in_ul = false; }
        $html .= '<p>' . atp_changelog_inline( $trimmed ) . "</p>\n";
    }

    if ( $in_ul ) { $html .= "</ul>\n"; }
    return $html;
}

/**
 * Inline markdown: bold, code, links.
 */
function atp_changelog_inline( $text ) {
    $text = esc_html( $text );
    // Code backticks
    $text = preg_replace( '/`([^`]+)`/', '<code>$1</code>', $text );
    // Bold **text**
    $text = preg_replace( '/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $text );
    // Links [text](url)
    $text = preg_replace( '/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2">$1</a>', $text );
    return $text;
}
