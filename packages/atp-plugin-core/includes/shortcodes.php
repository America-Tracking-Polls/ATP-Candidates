<?php
/**
 * Shortcode registration and rendering.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'atp_demo_register_shortcodes' );

function atp_demo_register_shortcodes() {
    // PHP-powered shortcodes — these get dedicated handlers that generate
    // dynamic HTML from structured data instead of static templates.
    $php_handlers = [
        'atp_logo'              => 'atp_demo_render_logo',
        'atp_cand_issues'       => 'atp_cand_render_issues',
        'atp_cand_endorsements' => 'atp_cand_render_endorsements',
        'atp_cand_social'       => 'atp_cand_render_social',
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
 * Generic HTML shortcode renderer — reads from DB or falls back to default.
 * For atp_cand_* shortcodes, also runs the candidate data token replacement.
 */
function atp_demo_render_shortcode( $atts, $content, $tag ) {
    $stored = get_option( 'atp_sc_' . $tag );
    $html   = ( $stored !== false && $stored !== '' ) ? $stored : atp_demo_get_default( $tag );
    $html   = str_replace( '{ATP_PLUGIN_URL}', ATP_DEMO_URL, $html );

    // Candidate page shortcodes get token replacement
    if ( str_starts_with( $tag, 'atp_cand_' ) && function_exists( 'atp_cand_replace_tokens' ) ) {
        $html = atp_cand_replace_tokens( $html );
    }

    return $html;
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
