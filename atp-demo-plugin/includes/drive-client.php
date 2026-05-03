<?php
/**
 * ATP Google Drive Client
 *
 * Service-account auth (RS256 JWT → access token), folder find-or-create,
 * and multipart file upload. Uses only WordPress HTTP API and the openssl
 * extension — no Composer dependencies.
 *
 * Credentials are loaded from a JSON key file on disk. The path is configured
 * in the White Label settings page and should live OUTSIDE the web root.
 *
 * @package ATP
 * @since   3.1.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

const ATP_DRIVE_TOKEN_TRANSIENT = 'atp_drive_access_token';
const ATP_DRIVE_SCOPE           = 'https://www.googleapis.com/auth/drive.file';
const ATP_DRIVE_TOKEN_URL       = 'https://oauth2.googleapis.com/token';
const ATP_DRIVE_API_BASE        = 'https://www.googleapis.com/drive/v3';
const ATP_DRIVE_UPLOAD_BASE     = 'https://www.googleapis.com/upload/drive/v3';

/**
 * Load and validate the service-account JSON key from disk.
 *
 * @return array|WP_Error  ['client_email'=>..., 'private_key'=>...] or error.
 */
function atp_drive_load_credentials( $path ) {
    if ( empty( $path ) ) {
        return new WP_Error( 'atp_drive_no_path', 'Service account JSON path is not configured.' );
    }
    if ( ! is_readable( $path ) ) {
        return new WP_Error( 'atp_drive_unreadable', 'Service account JSON file is missing or not readable: ' . $path );
    }
    $raw = file_get_contents( $path );
    if ( $raw === false ) {
        return new WP_Error( 'atp_drive_read_failed', 'Could not read service account JSON file.' );
    }
    $creds = json_decode( $raw, true );
    if ( ! is_array( $creds ) || empty( $creds['client_email'] ) || empty( $creds['private_key'] ) ) {
        return new WP_Error( 'atp_drive_bad_json', 'Service account JSON is missing client_email or private_key.' );
    }
    return $creds;
}

/**
 * Base64url encode (no padding) per JWT spec.
 */
function atp_drive_b64url( $data ) {
    return rtrim( strtr( base64_encode( $data ), '+/', '-_' ), '=' );
}

/**
 * Exchange a service-account JWT for an OAuth2 access token.
 * Cached in a transient until ~5 minutes before expiry.
 *
 * @return string|WP_Error  Access token or error.
 */
function atp_drive_get_access_token( $credentials_path, $force_refresh = false ) {
    if ( ! $force_refresh ) {
        $cached = get_transient( ATP_DRIVE_TOKEN_TRANSIENT );
        if ( $cached ) {
            return $cached;
        }
    }

    $creds = atp_drive_load_credentials( $credentials_path );
    if ( is_wp_error( $creds ) ) {
        return $creds;
    }

    $now = time();
    $header = [ 'alg' => 'RS256', 'typ' => 'JWT' ];
    $claims = [
        'iss'   => $creds['client_email'],
        'scope' => ATP_DRIVE_SCOPE,
        'aud'   => ATP_DRIVE_TOKEN_URL,
        'exp'   => $now + 3600,
        'iat'   => $now,
    ];

    $signing_input = atp_drive_b64url( wp_json_encode( $header ) ) . '.' . atp_drive_b64url( wp_json_encode( $claims ) );

    $signature = '';
    $ok = openssl_sign( $signing_input, $signature, $creds['private_key'], 'sha256WithRSAEncryption' );
    if ( ! $ok ) {
        return new WP_Error( 'atp_drive_sign_failed', 'Failed to sign JWT: ' . openssl_error_string() );
    }
    $jwt = $signing_input . '.' . atp_drive_b64url( $signature );

    $response = wp_remote_post( ATP_DRIVE_TOKEN_URL, [
        'timeout' => 20,
        'headers' => [ 'Content-Type' => 'application/x-www-form-urlencoded' ],
        'body'    => [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt,
        ],
    ] );

    if ( is_wp_error( $response ) ) {
        return $response;
    }
    $code = wp_remote_retrieve_response_code( $response );
    $body = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( $code !== 200 || empty( $body['access_token'] ) ) {
        $msg = $body['error_description'] ?? $body['error'] ?? 'Unknown error';
        return new WP_Error( 'atp_drive_token_failed', 'Token exchange failed (' . $code . '): ' . $msg );
    }

    $token = $body['access_token'];
    $ttl   = max( 60, (int) ( $body['expires_in'] ?? 3600 ) - 300 );
    set_transient( ATP_DRIVE_TOKEN_TRANSIENT, $token, $ttl );
    return $token;
}

/**
 * Find a child folder by name under $parent_id, or create it.
 *
 * @return string|WP_Error  Folder ID or error.
 */
function atp_drive_find_or_create_folder( $parent_id, $name, $token ) {
    $q = sprintf(
        "name = '%s' and '%s' in parents and mimeType = 'application/vnd.google-apps.folder' and trashed = false",
        str_replace( "'", "\\'", $name ),
        str_replace( "'", "\\'", $parent_id )
    );
    $list_url = ATP_DRIVE_API_BASE . '/files?' . http_build_query( [
        'q'                       => $q,
        'fields'                  => 'files(id,name)',
        'supportsAllDrives'       => 'true',
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
    if ( ! empty( $body['files'][0]['id'] ) ) {
        return $body['files'][0]['id'];
    }

    // Create
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
    if ( empty( $created['id'] ) ) {
        return new WP_Error( 'atp_drive_create_failed', 'Folder create returned no ID.' );
    }
    return $created['id'];
}

/**
 * Multipart upload a file to Drive.
 *
 * @return array|WP_Error  ['id'=>..., 'webViewLink'=>...] or error.
 */
function atp_drive_upload_file( $file_path, $upload_name, $mime, $folder_id, $token ) {
    if ( ! is_readable( $file_path ) ) {
        return new WP_Error( 'atp_drive_file_unreadable', 'File not readable: ' . $file_path );
    }
    $contents = file_get_contents( $file_path );
    if ( $contents === false ) {
        return new WP_Error( 'atp_drive_read_failed', 'Could not read file for upload.' );
    }

    $boundary = 'atpdrive' . wp_generate_password( 16, false );
    $metadata = wp_json_encode( [
        'name'    => $upload_name,
        'parents' => [ $folder_id ],
    ] );

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
    if ( empty( $out['id'] ) ) {
        return new WP_Error( 'atp_drive_upload_failed', 'Upload returned no file ID.' );
    }
    return $out;
}

/**
 * End-to-end test: auth, list parent folder, upload + delete a tiny test file.
 *
 * @return array|WP_Error  ['message'=>..., 'parent'=>...] on success.
 */
function atp_drive_test_connection( $credentials_path, $parent_folder_id ) {
    if ( empty( $parent_folder_id ) ) {
        return new WP_Error( 'atp_drive_no_folder', 'Folder ID is not configured.' );
    }
    $token = atp_drive_get_access_token( $credentials_path, true );
    if ( is_wp_error( $token ) ) return $token;

    // Verify parent folder is reachable.
    $resp = wp_remote_get(
        ATP_DRIVE_API_BASE . '/files/' . rawurlencode( $parent_folder_id ) . '?fields=id,name,mimeType&supportsAllDrives=true',
        [ 'timeout' => 20, 'headers' => [ 'Authorization' => 'Bearer ' . $token ] ]
    );
    if ( is_wp_error( $resp ) ) return $resp;
    if ( wp_remote_retrieve_response_code( $resp ) !== 200 ) {
        return new WP_Error( 'atp_drive_folder_unreachable',
            'Could not access folder. Make sure it is shared with the service account as Editor. Response: '
            . wp_remote_retrieve_body( $resp ) );
    }
    $info = json_decode( wp_remote_retrieve_body( $resp ), true );

    // Upload a tiny test file then delete it.
    $tmp = wp_tempnam( 'atp-drive-test' );
    file_put_contents( $tmp, 'ATP Drive connection test ' . gmdate( 'c' ) );
    $upload = atp_drive_upload_file( $tmp, 'atp-drive-test-' . time() . '.txt', 'text/plain', $parent_folder_id, $token );
    @unlink( $tmp );
    if ( is_wp_error( $upload ) ) return $upload;

    // Cleanup: delete the test file.
    wp_remote_request( ATP_DRIVE_API_BASE . '/files/' . rawurlencode( $upload['id'] ) . '?supportsAllDrives=true', [
        'method'  => 'DELETE',
        'timeout' => 20,
        'headers' => [ 'Authorization' => 'Bearer ' . $token ],
    ] );

    return [
        'message' => 'OK — authenticated, folder reachable, test file uploaded and removed.',
        'parent'  => $info['name'] ?? $parent_folder_id,
    ];
}
