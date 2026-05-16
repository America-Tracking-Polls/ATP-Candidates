<?php
/**
 * Shortcode registration and rendering.
 *
 * Architecture (override system, v2 — 2026-05-05):
 *
 *   Each shortcode has up to four sources of truth, resolved at render
 *   time. Per-shortcode overrides + a toggle let admins customize a
 *   single section without forking the whole plugin and without losing
 *   the ability to fall back to core.
 *
 *   ┌──────────────────────────────────────────────────────────────┐
 *   │  TEMPLATE source (which HTML to render):                     │
 *   │    1. atp_sc_<tag>           = per-site template override     │
 *   │    2. registry default       = shipped with the plugin        │
 *   │                                                              │
 *   │  Toggle: atp_sc_<tag>_disabled                                │
 *   │    when truthy, override is ignored and core default wins     │
 *   │                                                              │
 *   │  Shortcode attribute: source="core" | "override"              │
 *   │    forces a specific source for THIS render only,             │
 *   │    bypassing the toggle. Use on a test page to compare.       │
 *   │                                                              │
 *   │  DATA source (what fills {{tokens}}):                         │
 *   │    1. atp_sc_<tag>_data      = per-shortcode JSON data patch  │
 *   │    2. atp_cand_get_data()    = V3 JSON / candidate post meta  │
 *   │    Patches are MERGED on top of the V3 base.                  │
 *   └──────────────────────────────────────────────────────────────┘
 *
 *   The {{token}} substitution always runs LAST — regardless of whether
 *   the template came from override or default — so tokenized templates
 *   stay JSON-driven.
 *
 *   See OVERRIDE-SYSTEM.md for the user-facing write-up.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'atp_demo_register_shortcodes' );

function atp_demo_register_shortcodes() {
    // PHP-powered shortcodes that produce HTML dynamically from structured
    // data (rather than substituting tokens into a registry default).
    $php_handlers = [
        'atp_logo'              => 'atp_demo_render_logo',
        'atp_cand_issues'       => 'atp_cand_render_issues',
        'atp_cand_endorsements' => 'atp_cand_render_endorsements',
        'atp_cand_social'       => 'atp_cand_render_social',
        'atp_cand_signup'       => 'atp_cand_render_signup',
        'atp_cand_ai_context'   => 'atp_cand_render_ai_context',
    ];

    $registry = atp_demo_get_registry();
    foreach ( $registry as $group ) {
        foreach ( $group['shortcodes'] as $sc ) {
            if ( isset( $php_handlers[ $sc['tag'] ] ) && function_exists( $php_handlers[ $sc['tag'] ] ) ) {
                add_shortcode( $sc['tag'], $php_handlers[ $sc['tag'] ] );
            } else {
                add_shortcode( $sc['tag'], 'atp_demo_render_shortcode' );
            }
        }
    }
}

/**
 * Generic shortcode renderer with full override system.
 *
 * Supported attributes:
 *   source="core"     — force the registry default (preview the upcoming version)
 *   source="override" — force the per-site override even if disabled (preview the customization)
 */
function atp_demo_render_shortcode( $atts, $content, $tag ) {
    $atts   = shortcode_atts( [ 'source' => '' ], (array) $atts, $tag );
    $html   = atp_demo_resolve_template( $tag, $atts['source'] );
    $html   = str_replace( '{ATP_PLUGIN_URL}', ATP_DEMO_URL, $html );

    // Candidate-page shortcodes get token replacement merged with any
    // per-shortcode data patch.
    if ( str_starts_with( $tag, 'atp_cand_' ) && function_exists( 'atp_cand_replace_tokens' ) ) {
        $patch = atp_demo_get_data_patch( $tag );
        $html  = atp_cand_replace_tokens( $html, $patch );
    }

    return $html;
}

/**
 * Resolve which template HTML to render for a tag, honoring:
 *   - the source="..." attribute (preview override)
 *   - the per-site disable toggle
 *   - per-site override option, falling back to the registry default
 */
function atp_demo_resolve_template( $tag, $source = '' ) {
    $option   = 'atp_sc_' . $tag;
    $override = get_option( $option, '' );
    $default  = atp_demo_get_default( $tag );
    $disabled = (bool) get_option( 'atp_sc_' . $tag . '_disabled', false );
    $has_override = atp_demo_option_exists( $option );

    if ( $source === 'core' )     return $default;
    if ( $source === 'override' ) return $has_override ? $override : $default;

    if ( $disabled || ! $has_override ) return $default;
    return $override;
}

function atp_demo_option_exists( $option_name ) {
    global $wpdb;
    return $wpdb->get_var(
        $wpdb->prepare(
            "SELECT option_id FROM {$wpdb->options} WHERE option_name = %s LIMIT 1",
            $option_name
        )
    ) !== null;
}

/**
 * Pull the per-shortcode data patch (JSON object) from wp_options, decoded.
 * Returns an empty array if not set or unparseable.
 *
 * Stored in atp_sc_<tag>_data as a JSON string. Merges on top of V3 JSON
 * inside atp_cand_replace_tokens() so the patch only overrides the keys
 * it specifies; anything missing falls through to the V3 source of truth.
 */
function atp_demo_get_data_patch( $tag ) {
    $raw = get_option( 'atp_sc_' . $tag . '_data', '' );
    if ( ! is_string( $raw ) || $raw === '' ) return [];
    $decoded = json_decode( $raw, true );
    return is_array( $decoded ) ? $decoded : [];
}

/**
 * Logo shortcode — dynamic plugin URL, supports variant attribute.
 * Usage: [atp_logo] [atp_logo variant="red-white" width="200px"]
 */
function atp_demo_render_logo( $atts ) {
    $atts = shortcode_atts( [
        'variant' => 'standard',
        'width'   => '160px',
        'alt'     => 'America Tracking Polls',
        'class'   => '',
        'style'   => '',
    ], $atts, 'atp_logo' );

    $map = [
        'standard'   => 'ATP-Logo-Standard.png',
        'blue-white' => 'ATP-Logo-Blue-White.png',
        'red-white'  => 'ATP-Logo-Red-White.png',
    ];

    $file  = $map[ $atts['variant'] ] ?? 'ATP-Logo-Standard.png';
    $url   = ATP_DEMO_URL . 'assets/images/' . $file;
    $style = 'width:' . esc_attr( $atts['width'] ) . ';height:auto;' . esc_attr( $atts['style'] );

    return '<img src="' . esc_url( $url ) . '"'
        . ' alt="' . esc_attr( $atts['alt'] ) . '"'
        . ' style="' . $style . '"'
        . ( $atts['class'] ? ' class="' . esc_attr( $atts['class'] ) . '"' : '' )
        . '>';
}

/**
 * Find the default content for a tag from the registry.
 */
function atp_demo_get_default( $tag ) {
    foreach ( atp_demo_get_registry() as $group ) {
        foreach ( $group['shortcodes'] as $sc ) {
            if ( $sc['tag'] === $tag ) {
                return $sc['default'] ?? '';
            }
        }
    }
    return '';
}
