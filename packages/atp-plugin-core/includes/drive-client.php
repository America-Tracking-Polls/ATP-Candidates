<?php
/**
 * ATP Google Drive Client (OAuth user flow)
 *
 * Lets a WP admin connect their Google account, pick a Drive folder, and
 * have intake submissions copied into that folder under per-submission
 * subfolders (YYYY-MM-DD_Candidate-Name_Office-Slug).
 *
 * Storage of credentials:
 *   atp_drive_oauth = [
 *     'client_id'        => string  (from Google Cloud OAuth client)
 *     'client_secret'    => string  (from Google Cloud OAuth client)
 *     'refresh_token'    => string  (issued on first connect)
 *     'connected_email'  => string  (display only)
 *   ]
 *   atp_drive_config = [
 *     'folder_id'   => string  (the picked Drive folder ID)
 *     'folder_name' => string  (display only)
 *   ]
 *
 * Security: refresh_token is stored in wp_options. WP options table is
 * not exposed publicly; admin-only. If the host wants extra protection
 * the token could be encrypted with a key in wp-config.php — left as a
 * follow-up.
 *
 * @package ATP
 * @since   3.2.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

const ATP_DRIVE_TOKEN_TRANSIENT = 'atp_drive_access_token';
const ATP_DRIVE_STATE_TRANSIENT = 'atp_drive_oauth_state';
const ATP_DRIVE_SCOPE           = 'https://www.googleapis.com/auth/drive';
const ATP_DRIVE_AUTHORIZE_URL   = 'https://accounts.google.com/o/oauth2/v2/auth';
const ATP_DRIVE_TOKEN_URL       = 'https://oauth2.googleapis.com/token';
const ATP_DRIVE_API_BASE        = 'https://www.googleapis.com/drive/v3';
const ATP_DRIVE_UPLOAD_BASE     = 'https://www.googleapis.com/upload/drive/v3';
const ATP_DRIVE_USERINFO_URL    = 'https://www.googleapis.com/oauth2/v2/userinfo';

/* ─────────────────────────────────────────────────────────────────────────
   Configuration helpers
   ───────────────────────────────────────────────────────────────────────── */

function atp_drive_oauth_get() {
    return wp_parse_args( get_option( 'atp_drive_oauth', [] ), [
        'client_id'       => '',
        'client_secret'   => '',
        'refresh_token'   => '',
        'connected_email' => '',
    ] );
}

function atp_drive_oauth_set( $patch ) {
    $current = atp_drive_oauth_get();
    update_option( 'atp_drive_oauth', array_merge( $current, $patch ) );
    delete_transient( ATP_DRIVE_TOKEN_TRANSIENT );
}

function atp_drive_oauth_clear_tokens() {
    $current = atp_drive_oauth_get();
    $current['refresh_token']   = '';
    $current['connected_email'] = '';
    update_option( 'atp_drive_oauth', $current );
    delete_transient( ATP_DRIVE_TOKEN_TRANSIENT );
}

function atp_drive_redirect_uri() {
    return admin_url( 'admin.php?page=atp-whitelabel&atp_drive_oauth=callback' );
}

function atp_drive_is_connected() {
    $o = atp_drive_oauth_get();
    return ! empty( $o['client_id'] ) && ! empty( $o['client_secret'] ) && ! empty( $o['refresh_token'] );
}

function atp_drive_is_configured() {
    if ( ! atp_drive_is_connected() ) return false;
    $cfg = get_option( 'atp_drive_config', [] );
    return ! empty( $cfg['folder_id'] );
}

/* ─────────────────────────────────────────────────────────────────────────
   OAuth: authorize URL + callback handler
   ───────────────────────────────────────────────────────────────────────── */

function atp_drive_authorize_url() {
    $o = atp_drive_oauth_get();
    if ( empty( $o['client_id'] ) ) return '';

    $state = wp_generate_password( 32, false );
    set_transient( ATP_DRIVE_STATE_TRANSIENT, $state, 15 * MINUTE_IN_SECONDS );

    return ATP_DRIVE_AUTHORIZE_URL . '?' . http_build_query( [
        'client_id'              => $o['client_id'],
        'redirect_uri'           => atp_drive_redirect_uri(),
        'response_type'          => 'code',
        'scope'                  => ATP_DRIVE_SCOPE . ' https://www.googleapis.com/auth/userinfo.email',
        'access_type'            => 'offline',
        'prompt'                 => 'consent',
        'include_granted_scopes' => 'true',
        'state'                  => $state,
    ] );
}

/**
 * Exchange an auth code for refresh_token + access_token, then fetch the
 * connected account's email.
 *
 * @return true|WP_Error
 */
function atp_drive_handle_oauth_callback( $code, $state ) {
    $expected_state = get_transient( ATP_DRIVE_STATE_TRANSIENT );
    delete_transient( ATP_DRIVE_STATE_TRANSIENT );
    if ( ! $expected_state || ! hash_equals( $expected_state, (string) $state ) ) {
        return new WP_Error( 'atp_drive_state', 'OAuth state token mismatch. Please try connecting again.' );
    }

    $o = atp_drive_oauth_get();
    if ( empty( $o['client_id'] ) || empty( $o['client_secret'] ) ) {
        return new WP_Error( 'atp_drive_no_client', 'Save the OAuth Client ID and Client Secret first.' );
    }

    $resp = wp_remote_post( ATP_DRIVE_TOKEN_URL, [
        'timeout' => 20,
        'headers' => [ 'Content-Type' => 'application/x-www-form-urlencoded' ],
        'body'    => [
            'code'          => $code,
            'client_id'     => $o['client_id'],
            'client_secret' => $o['client_secret'],
            'redirect_uri'  => atp_drive_redirect_uri(),
            'grant_type'    => 'authorization_code',
        ],
    ] );
    if ( is_wp_error( $resp ) ) return $resp;
    $body = json_decode( wp_remote_retrieve_body( $resp ), true );
    if ( wp_remote_retrieve_response_code( $resp ) !== 200 || empty( $body['access_token'] ) ) {
        $msg = $body['error_description'] ?? $body['error'] ?? 'Token exchange failed.';
        return new WP_Error( 'atp_drive_token', $msg );
    }
    if ( empty( $body['refresh_token'] ) ) {
        return new WP_Error( 'atp_drive_no_refresh',
            'Google did not return a refresh token. Revoke the previous grant in your Google Account → Security → Third-party apps and try connecting again.' );
    }

    $email = '';
    $info = wp_remote_get( ATP_DRIVE_USERINFO_URL, [
        'timeout' => 15,
        'headers' => [ 'Authorization' => 'Bearer ' . $body['access_token'] ],
    ] );
    if ( ! is_wp_error( $info ) && wp_remote_retrieve_response_code( $info ) === 200 ) {
        $u = json_decode( wp_remote_retrieve_body( $info ), true );
        if ( ! empty( $u['email'] ) ) $email = $u['email'];
    }

    atp_drive_oauth_set( [
        'refresh_token'   => $body['refresh_token'],
        'connected_email' => $email,
    ] );
    set_transient( ATP_DRIVE_TOKEN_TRANSIENT, $body['access_token'], max( 60, (int) ( $body['expires_in'] ?? 3600 ) - 300 ) );
    return true;
}

/* ─────────────────────────────────────────────────────────────────────────
   Access tokens (refresh-token flow)
   ───────────────────────────────────────────────────────────────────────── */

function atp_drive_get_access_token( $force_refresh = false ) {
    if ( ! $force_refresh ) {
        $cached = get_transient( ATP_DRIVE_TOKEN_TRANSIENT );
        if ( $cached ) return $cached;
    }
    $o = atp_drive_oauth_get();
    if ( empty( $o['client_id'] ) || empty( $o['client_secret'] ) || empty( $o['refresh_token'] ) ) {
        return new WP_Error( 'atp_drive_not_connected', 'Google Drive is not connected. Connect it from White Label Settings.' );
    }
    $resp = wp_remote_post( ATP_DRIVE_TOKEN_URL, [
        'timeout' => 20,
        'headers' => [ 'Content-Type' => 'application/x-www-form-urlencoded' ],
        'body'    => [
            'client_id'     => $o['client_id'],
            'client_secret' => $o['client_secret'],
            'refresh_token' => $o['refresh_token'],
            'grant_type'    => 'refresh_token',
        ],
    ] );
    if ( is_wp_error( $resp ) ) return $resp;
    $code = wp_remote_retrieve_response_code( $resp );
    $body = json_decode( wp_remote_retrieve_body( $resp ), true );
    if ( $code !== 200 || empty( $body['access_token'] ) ) {
        $msg = $body['error_description'] ?? $body['error'] ?? 'Token refresh failed.';
        if ( ( $body['error'] ?? '' ) === 'invalid_grant' ) {
            atp_drive_oauth_clear_tokens();
            $msg .= ' (Refresh token revoked or expired — please reconnect.)';
        }
        return new WP_Error( 'atp_drive_refresh', $msg );
    }
    $token = $body['access_token'];
    $ttl   = max( 60, (int) ( $body['expires_in'] ?? 3600 ) - 300 );
    set_transient( ATP_DRIVE_TOKEN_TRANSIENT, $token, $ttl );
    return $token;
}

/* ─────────────────────────────────────────────────────────────────────────
   Drive API helpers (folders + upload)
   ───────────────────────────────────────────────────────────────────────── */

/**
 * List Drive folders under a given parent (default = root). Used by the
 * in-admin folder browser.
 *
 * @return array|WP_Error  list of ['id'=>, 'name'=>, ...] or error.
 */
function atp_drive_list_folders( $parent_id = 'root', $token = null ) {
    if ( ! $token ) {
        $token = atp_drive_get_access_token();
        if ( is_wp_error( $token ) ) return $token;
    }

    $folders = [];
    $seen_ids = [];

    // 1) Folders that have $parent_id as a parent (My Drive structure +
    //    children of any folder you've drilled into).
    $q_parent = sprintf(
        "'%s' in parents and mimeType = 'application/vnd.google-apps.folder' and trashed = false",
        str_replace( "'", "\\'", $parent_id )
    );
    $url = ATP_DRIVE_API_BASE . '/files?' . http_build_query( [
        'q'                         => $q_parent,
        'fields'                    => 'files(id,name,parents,shared,ownedByMe)',
        'orderBy'                   => 'name',
        'pageSize'                  => 200,
        'supportsAllDrives'         => 'true',
        'includeItemsFromAllDrives' => 'true',
    ] );
    $resp = wp_remote_get( $url, [
        'timeout' => 20,
        'headers' => [ 'Authorization' => 'Bearer ' . $token ],
    ] );
    if ( is_wp_error( $resp ) ) return $resp;
    if ( wp_remote_retrieve_response_code( $resp ) !== 200 ) {
        return new WP_Error( 'atp_drive_list', wp_remote_retrieve_body( $resp ) );
    }
    $body = json_decode( wp_remote_retrieve_body( $resp ), true );
    foreach ( $body['files'] ?? [] as $f ) {
        if ( isset( $seen_ids[ $f['id'] ] ) ) continue;
        $seen_ids[ $f['id'] ] = 1;
        $folders[] = $f;
    }

    // 2) At the root, also fetch "Shared with me" folders. They don't have
    //    'root' as a parent so they wouldn't appear in the query above.
    if ( $parent_id === 'root' ) {
        $q_shared = "sharedWithMe = true and mimeType = 'application/vnd.google-apps.folder' and trashed = false";
        $url2 = ATP_DRIVE_API_BASE . '/files?' . http_build_query( [
            'q'                         => $q_shared,
            'fields'                    => 'files(id,name,parents,shared,ownedByMe)',
            'orderBy'                   => 'name',
            'pageSize'                  => 200,
            'supportsAllDrives'         => 'true',
            'includeItemsFromAllDrives' => 'true',
        ] );
        $resp2 = wp_remote_get( $url2, [
            'timeout' => 20,
            'headers' => [ 'Authorization' => 'Bearer ' . $token ],
        ] );
        if ( ! is_wp_error( $resp2 ) && wp_remote_retrieve_response_code( $resp2 ) === 200 ) {
            $body2 = json_decode( wp_remote_retrieve_body( $resp2 ), true );
            foreach ( $body2['files'] ?? [] as $f ) {
                if ( isset( $seen_ids[ $f['id'] ] ) ) continue;
                $seen_ids[ $f['id'] ] = 1;
                $f['_shared_with_me'] = true; // surfaces in the picker UI
                $folders[] = $f;
            }
        }
    }

    return $folders;
}

function atp_drive_get_folder_meta( $folder_id, $token = null ) {
    if ( ! $token ) {
        $token = atp_drive_get_access_token();
        if ( is_wp_error( $token ) ) return $token;
    }
    $url = ATP_DRIVE_API_BASE . '/files/' . rawurlencode( $folder_id ) . '?fields=id,name,mimeType,parents&supportsAllDrives=true';
    $resp = wp_remote_get( $url, [
        'timeout' => 15,
        'headers' => [ 'Authorization' => 'Bearer ' . $token ],
    ] );
    if ( is_wp_error( $resp ) ) return $resp;
    if ( wp_remote_retrieve_response_code( $resp ) !== 200 ) {
        return new WP_Error( 'atp_drive_meta', wp_remote_retrieve_body( $resp ) );
    }
    return json_decode( wp_remote_retrieve_body( $resp ), true );
}

function atp_drive_find_or_create_folder( $parent_id, $name, $token ) {
    $q = sprintf(
        "name = '%s' and '%s' in parents and mimeType = 'application/vnd.google-apps.folder' and trashed = false",
        str_replace( "'", "\\'", $name ),
        str_replace( "'", "\\'", $parent_id )
    );
    $list_url = ATP_DRIVE_API_BASE . '/files?' . http_build_query( [
        'q'                         => $q,
        'fields'                    => 'files(id,name)',
        'supportsAllDrives'         => 'true',
        'includeItemsFromAllDrives' => 'true',
    ] );
    $resp = wp_remote_get( $list_url, [
        'timeout' => 20,
        'headers' => [ 'Authorization' => 'Bearer ' . $token ],
    ] );
    if ( is_wp_error( $resp ) ) return $resp;
    if ( wp_remote_retrieve_response_code( $resp ) !== 200 ) {
        return new WP_Error( 'atp_drive_list_failed', 'Folder lookup failed: ' . wp_remote_retrieve_body( $resp ) );
    }
    $body = json_decode( wp_remote_retrieve_body( $resp ), true );
    if ( ! empty( $body['files'][0]['id'] ) ) return $body['files'][0]['id'];

    $create_resp = wp_remote_post( ATP_DRIVE_API_BASE . '/files?supportsAllDrives=true', [
        'timeout' => 20,
        'headers' => [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json',
        ],
        'body' => wp_json_encode( [
            'name'     => $name,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents'  => [ $parent_id ],
        ] ),
    ] );
    if ( is_wp_error( $create_resp ) ) return $create_resp;
    if ( wp_remote_retrieve_response_code( $create_resp ) >= 300 ) {
        return new WP_Error( 'atp_drive_create_failed', 'Folder create failed: ' . wp_remote_retrieve_body( $create_resp ) );
    }
    $created = json_decode( wp_remote_retrieve_body( $create_resp ), true );
    if ( empty( $created['id'] ) ) return new WP_Error( 'atp_drive_create_failed', 'Folder create returned no ID.' );
    return $created['id'];
}

function atp_drive_upload_file( $file_path, $upload_name, $mime, $folder_id, $token ) {
    if ( ! is_readable( $file_path ) ) {
        return new WP_Error( 'atp_drive_file_unreadable', 'File not readable: ' . $file_path );
    }
    $contents = file_get_contents( $file_path );
    if ( $contents === false ) return new WP_Error( 'atp_drive_read_failed', 'Could not read file for upload.' );

    $boundary = 'atpdrive' . wp_generate_password( 16, false );
    $metadata = wp_json_encode( [ 'name' => $upload_name, 'parents' => [ $folder_id ] ] );

    $body  = "--{$boundary}\r\n";
    $body .= "Content-Type: application/json; charset=UTF-8\r\n\r\n";
    $body .= $metadata . "\r\n";
    $body .= "--{$boundary}\r\n";
    $body .= "Content-Type: " . $mime . "\r\n\r\n";
    $body .= $contents . "\r\n";
    $body .= "--{$boundary}--";

    $url = ATP_DRIVE_UPLOAD_BASE . '/files?uploadType=multipart&supportsAllDrives=true&fields=id,webViewLink,webContentLink';
    $resp = wp_remote_post( $url, [
        'timeout' => 60,
        'headers' => [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'multipart/related; boundary=' . $boundary,
        ],
        'body' => $body,
    ] );
    if ( is_wp_error( $resp ) ) return $resp;
    if ( wp_remote_retrieve_response_code( $resp ) >= 300 ) {
        return new WP_Error( 'atp_drive_upload_failed', 'Upload failed: ' . wp_remote_retrieve_body( $resp ) );
    }
    $out = json_decode( wp_remote_retrieve_body( $resp ), true );
    if ( empty( $out['id'] ) ) return new WP_Error( 'atp_drive_upload_failed', 'Upload returned no file ID.' );
    return $out;
}

/**
 * AJAX handler for the in-admin folder browser. Returns a JSON list of
 * subfolders under the given parent_id.
 */
add_action( 'wp_ajax_atp_drive_browse', 'atp_drive_ajax_browse' );
function atp_drive_ajax_browse() {
    check_ajax_referer( 'atp_drive_browse' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Unauthorized', 403 );
    }
    $parent = sanitize_text_field( $_POST['parent_id'] ?? 'root' );
    $folders = atp_drive_list_folders( $parent );
    if ( is_wp_error( $folders ) ) {
        wp_send_json_error( $folders->get_error_message() );
    }
    wp_send_json_success( array_values( array_map( function( $f ) {
        return [
            'id'              => $f['id'],
            'name'            => $f['name'],
            'shared_with_me'  => ! empty( $f['_shared_with_me'] ),
        ];
    }, $folders ) ) );
}

/**
 * Round-trip a tiny test file in/out of the picked folder. Used by the
 * "Test Drive Connection" button.
 *
 * @return array|WP_Error
 */
function atp_drive_test_connection() {
    $cfg = get_option( 'atp_drive_config', [] );
    $folder_id = $cfg['folder_id'] ?? '';
    if ( ! $folder_id ) return new WP_Error( 'atp_drive_no_folder', 'No folder picked yet.' );

    $token = atp_drive_get_access_token( true );
    if ( is_wp_error( $token ) ) return $token;

    $info = atp_drive_get_folder_meta( $folder_id, $token );
    if ( is_wp_error( $info ) ) return $info;

    $tmp = wp_tempnam( 'atp-drive-test' );
    file_put_contents( $tmp, 'ATP Drive connection test ' . gmdate( 'c' ) );
    $upload = atp_drive_upload_file( $tmp, 'atp-drive-test-' . time() . '.txt', 'text/plain', $folder_id, $token );
    @unlink( $tmp );
    if ( is_wp_error( $upload ) ) return $upload;

    wp_remote_request( ATP_DRIVE_API_BASE . '/files/' . rawurlencode( $upload['id'] ) . '?supportsAllDrives=true', [
        'method'  => 'DELETE',
        'timeout' => 20,
        'headers' => [ 'Authorization' => 'Bearer ' . $token ],
    ] );

    return [
        'message' => 'OK — authenticated, folder reachable, test file uploaded and removed.',
        'folder'  => $info['name'] ?? $folder_id,
    ];
}
