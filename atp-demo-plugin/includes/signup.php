<?php
/**
 * ATP Candidate Signup
 *
 * Powers the [atp_cand_signup] shortcode: renders a name/email/phone
 * signup form with TCPA-compliant SMS opt-in, captures submissions as
 * `atp_subscriber` posts, and emails the campaign contact on each
 * submission.
 *
 * Reads from the V3 JSON / candidate post meta:
 *   identity.display_name, legal_compliance.committee_name,
 *   legal_compliance.paid_for_by, legal_compliance.campaign_email_legal,
 *   social_media.* (for the social row above the form)
 *
 * @package ATP
 * @since 3.4.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* ─────────────────────────────────────────────────────────────────────────
   Custom post type for captured signups
   ───────────────────────────────────────────────────────────────────────── */

add_action( 'init', 'atp_cand_signup_register_cpt' );
function atp_cand_signup_register_cpt() {
    register_post_type( 'atp_subscriber', [
        'labels'        => [ 'name' => 'Subscribers', 'singular_name' => 'Subscriber' ],
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => 'edit.php?post_type=atp_candidate',
        'supports'      => [ 'title' ],
        'capability_type' => 'post',
    ] );
}

/* ─────────────────────────────────────────────────────────────────────────
   Renderer for the [atp_cand_signup] shortcode
   ───────────────────────────────────────────────────────────────────────── */

function atp_cand_render_signup( $atts = [] ) {
    $data = function_exists( 'atp_cand_get_data' ) ? atp_cand_get_data() : [];

    // Pull values with sensible fallbacks.
    $display_name    = $data['display_name']       ?? $data['legal_name'] ?? 'the campaign';
    $committee_full  = $data['committee_name']     ?? ( $display_name . ' Campaign' );
    $committee_short = $display_name; // short label for headings
    $paid_for_by     = $data['paidfor_text']       ?? ( 'Political advertising paid for by ' . $committee_full );
    $privacy_path    = '/privacy-policy/';
    $privacy_url     = home_url( $privacy_path );

    // Social icons row — only render platforms that have URLs.
    $platforms = [
        'facebook'  => [ 'Facebook',  '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H8v-2.9h2.4V9.9c0-2.4 1.4-3.7 3.6-3.7 1 0 2.1.2 2.1.2v2.3h-1.2c-1.2 0-1.5.7-1.5 1.5v1.8h2.6l-.4 2.9h-2.2v7A10 10 0 0 0 22 12z"/></svg>' ],
        'twitter_x' => [ 'X',         '<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M18 2h3l-7 8 8 12h-6l-5-7-5 7H3l8-9L3 2h6l4 6 5-6z"/></svg>' ],
        'youtube'   => [ 'YouTube',   '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M23 7s-.2-1.6-.9-2.3c-.8-.9-1.7-.9-2.1-1C16.7 3.4 12 3.4 12 3.4s-4.7 0-8 .3c-.4.1-1.3.1-2.1 1C1.2 5.4 1 7 1 7s-.2 1.9-.2 3.8v1.8c0 1.9.2 3.8.2 3.8s.2 1.6.9 2.3c.8.9 1.9.9 2.4 1 1.7.1 7.7.3 7.7.3s4.7 0 8-.3c.4-.1 1.3-.1 2.1-1 .7-.7.9-2.3.9-2.3s.2-1.9.2-3.8v-1.8C23.2 8.9 23 7 23 7zm-13.2 7.7V8.3l6 3.2-6 3.2z"/></svg>' ],
        'instagram' => [ 'Instagram', '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 2.2c3.2 0 3.6 0 4.8.1 1.2.1 2 .2 2.4.4.6.2 1 .5 1.4 1 .5.4.8.8 1 1.4.2.4.3 1.2.4 2.4.1 1.2.1 1.6.1 4.8s0 3.6-.1 4.8c-.1 1.2-.2 2-.4 2.4-.2.6-.5 1-1 1.4-.4.5-.8.8-1.4 1-.4.2-1.2.3-2.4.4-1.2.1-1.6.1-4.8.1s-3.6 0-4.8-.1c-1.2-.1-2-.2-2.4-.4-.6-.2-1-.5-1.4-1-.5-.4-.8-.8-1-1.4-.2-.4-.3-1.2-.4-2.4C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.8c.1-1.2.2-2 .4-2.4.2-.6.5-1 1-1.4.4-.5.8-.8 1.4-1 .4-.2 1.2-.3 2.4-.4C8.4 2.2 8.8 2.2 12 2.2zm0 1.8c-3.2 0-3.6 0-4.7.1-1.1.1-1.7.2-2.1.3-.5.2-.9.4-1.3.8-.4.4-.6.8-.8 1.3-.1.4-.3 1-.3 2.1-.1 1.2-.1 1.5-.1 4.7s0 3.5.1 4.7c.1 1.1.2 1.7.3 2.1.2.5.4.9.8 1.3.4.4.8.6 1.3.8.4.1 1 .3 2.1.3 1.2.1 1.5.1 4.7.1s3.5 0 4.7-.1c1.1-.1 1.7-.2 2.1-.3.5-.2.9-.4 1.3-.8.4-.4.6-.8.8-1.3.1-.4.3-1 .3-2.1.1-1.2.1-1.5.1-4.7s0-3.5-.1-4.7c-.1-1.1-.2-1.7-.3-2.1-.2-.5-.4-.9-.8-1.3-.4-.4-.8-.6-1.3-.8-.4-.1-1-.3-2.1-.3-1.2-.1-1.5-.1-4.7-.1zm0 3.1a4.9 4.9 0 1 1 0 9.8 4.9 4.9 0 0 1 0-9.8zm0 8.1a3.2 3.2 0 1 0 0-6.4 3.2 3.2 0 0 0 0 6.4zm6.2-8.3a1.1 1.1 0 1 1-2.3 0 1.1 1.1 0 0 1 2.3 0z"/></svg>' ],
        'tiktok'    => [ 'TikTok',    '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M20 7.5a6.5 6.5 0 0 1-3.8-1.2v6.4a6 6 0 1 1-6-6c.3 0 .6 0 .9.1V10a3 3 0 1 0 2.1 2.9V2h2.5A4 4 0 0 0 20 5z"/></svg>' ],
        'linkedin'  => [ 'LinkedIn',  '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14zM8.3 18.5v-8H5.8v8h2.5zM7 9.4a1.4 1.4 0 1 0 0-2.8 1.4 1.4 0 0 0 0 2.8zm11.3 9.1v-4.4c0-2.3-1.2-3.4-2.9-3.4-1.3 0-2 .7-2.3 1.2v-1H10.5v8H13v-4.4c0-1 .2-1.9 1.4-1.9s1.4 1.1 1.4 2v4.3h2.5z"/></svg>' ],
    ];
    $icons_html = '';
    foreach ( $platforms as $key => [ $label, $svg ] ) {
        if ( empty( $data[ $key ] ) ) continue;
        $bg = ( $key === 'youtube' || $key === 'twitter_x' ) ? '#000' : ( $key === 'facebook' ? '#1877f2' : ( $key === 'instagram' ? '#e4405f' : '#000' ) );
        $icons_html .= sprintf(
            '<a href="%s" target="_blank" rel="noopener" aria-label="%s" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:%s;color:#fff;border-radius:4px;text-decoration:none">%s</a>',
            esc_url( $data[ $key ] ),
            esc_attr( $label ),
            esc_attr( $bg ),
            $svg
        );
    }

    // Build the HTML — start from the registry default if available so admins
    // can override the markup; fall back to a hardcoded shell.
    $template = function_exists( 'atp_demo_get_default' ) ? atp_demo_get_default( 'atp_cand_signup' ) : '';
    if ( ! $template ) {
        return '<!-- atp_cand_signup: missing default template -->';
    }

    // Substitute placeholders. {{display_name}} is handled by atp_cand_replace_tokens
    // since it's a plain V3 field; the rest are dynamic and substituted here.
    $replacements = [
        '{{committee_short}}' => esc_html( $committee_short ),
        '{{committee_full}}'  => esc_html( $committee_full ),
        '{{paid_for_by}}'     => esc_html( $paid_for_by ),
        '{{privacy_url}}'     => esc_url( $privacy_url ),
        '{{social_icons}}'    => $icons_html,
        '{{ajax_url}}'        => esc_url( admin_url( 'admin-ajax.php' ) ),
        '{{nonce}}'           => esc_attr( wp_create_nonce( 'atp_cand_signup' ) ),
    ];
    $html = strtr( $template, $replacements );

    // Run the standard candidate token replacement for {{display_name}} etc.
    if ( function_exists( 'atp_cand_replace_tokens' ) ) {
        $html = atp_cand_replace_tokens( $html );
    }
    return $html;
}

/* ─────────────────────────────────────────────────────────────────────────
   AJAX handler — capture the submission
   ───────────────────────────────────────────────────────────────────────── */

add_action( 'wp_ajax_atp_cand_signup_save',        'atp_cand_signup_save' );
add_action( 'wp_ajax_nopriv_atp_cand_signup_save', 'atp_cand_signup_save' );
function atp_cand_signup_save() {
    if ( ! check_ajax_referer( 'atp_cand_signup', 'nonce', false ) ) {
        wp_send_json_error( 'Security check failed. Refresh the page and try again.', 400 );
    }

    $first    = sanitize_text_field( wp_unslash( $_POST['name_first'] ?? '' ) );
    $last     = sanitize_text_field( wp_unslash( $_POST['name_last']  ?? '' ) );
    $email    = sanitize_email(      wp_unslash( $_POST['email']      ?? '' ) );
    $phone    = sanitize_text_field( wp_unslash( $_POST['phone']      ?? '' ) );
    $sms      = ! empty( $_POST['sms_optin'] );

    if ( ! $first || ! $last ) wp_send_json_error( 'Please fill in your name.', 400 );
    if ( ! is_email( $email ) ) wp_send_json_error( 'Please provide a valid email address.', 400 );

    $name = trim( $first . ' ' . $last );
    $pid  = wp_insert_post( [
        'post_title'  => $name . ' (' . $email . ')',
        'post_type'   => 'atp_subscriber',
        'post_status' => 'publish',
    ] );
    if ( is_wp_error( $pid ) ) wp_send_json_error( $pid->get_error_message(), 500 );

    update_post_meta( $pid, 'name_first', $first );
    update_post_meta( $pid, 'name_last',  $last );
    update_post_meta( $pid, 'email',      $email );
    update_post_meta( $pid, 'phone',      $phone );
    update_post_meta( $pid, 'sms_optin',  $sms ? '1' : '0' );
    update_post_meta( $pid, 'submitted_at', current_time( 'mysql' ) );
    update_post_meta( $pid, 'ip',         atp_cand_signup_client_ip() );
    update_post_meta( $pid, 'user_agent', sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ?? '' ) );

    atp_cand_signup_notify( [
        'name'  => $name,
        'email' => $email,
        'phone' => $phone,
        'sms'   => $sms,
    ], $pid );

    wp_send_json_success( [ 'id' => $pid ] );
}

function atp_cand_signup_client_ip() {
    foreach ( [ 'HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR' ] as $h ) {
        if ( ! empty( $_SERVER[ $h ] ) ) {
            $ip = sanitize_text_field( explode( ',', $_SERVER[ $h ] )[0] );
            if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) return $ip;
        }
    }
    return '';
}

/* ─────────────────────────────────────────────────────────────────────────
   Notification email — to the campaign contact (from V3 JSON if available).
   ───────────────────────────────────────────────────────────────────────── */

function atp_cand_signup_notify( $info, $pid ) {
    $data = function_exists( 'atp_cand_get_data' ) ? atp_cand_get_data() : [];
    $to   = $data['campaign_email_legal'] ?? $data['contact_email'] ?? get_option( 'admin_email' );
    if ( ! is_email( $to ) ) return;

    $candidate = $data['display_name'] ?? get_bloginfo( 'name' );
    $admin     = admin_url( 'post.php?action=edit&post=' . $pid );

    $subject = 'New signup: ' . $info['name'] . ' — ' . $candidate;
    $body  = '<!doctype html><html><body style="font-family:Arial,sans-serif;max-width:560px;margin:0 auto;padding:20px">';
    $body .= '<h2 style="margin:0 0 12px">New signup</h2>';
    $body .= '<table style="width:100%;border-collapse:collapse;margin-bottom:20px">';
    foreach ( [
        'Name'      => $info['name'],
        'Email'     => $info['email'],
        'Phone'     => $info['phone']  ?: '—',
        'SMS opt-in'=> $info['sms']    ? 'Yes' : 'No',
        'Candidate' => $candidate,
    ] as $l => $v ) {
        $body .= '<tr><td style="padding:6px 10px;border:1px solid #eee;color:#666;width:120px">'
              . esc_html( $l ) . '</td><td style="padding:6px 10px;border:1px solid #eee;font-weight:600">'
              . esc_html( $v ) . '</td></tr>';
    }
    $body .= '</table>';
    $body .= '<a href="' . esc_url( $admin ) . '" style="display:inline-block;background:#0e1235;color:#fff;padding:10px 18px;border-radius:4px;text-decoration:none;font-weight:700">Open in WP admin →</a>';
    $body .= '</body></html>';
    wp_mail( $to, $subject, $body, [ 'Content-Type: text/html; charset=UTF-8' ] );
}
