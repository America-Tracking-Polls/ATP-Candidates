<?php
/**
 * ATP File Upload Handler
 *
 * Handles file uploads from the intake form. Defaults to WordPress media
 * library. Google Drive adapter can be enabled via settings.
 *
 * @package ATP
 * @since   3.1.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_ajax_atp_upload_file', 'atp_handle_file_upload' );
add_action( 'wp_ajax_nopriv_atp_upload_file', 'atp_handle_file_upload' );

function atp_handle_file_upload() {
    check_ajax_referer( 'atp_form', 'nonce' );

    if ( empty( $_FILES['file'] ) ) {
        wp_send_json_error( 'No file uploaded.' );
    }

    $file  = $_FILES['file'];
    $field = sanitize_key( $_POST['field'] ?? '' );
    $candidate = sanitize_text_field( $_POST['candidate'] ?? 'candidate' );
    $office = sanitize_text_field( $_POST['office'] ?? '' );

    // Validate file type
    $allowed = atp_upload_allowed_types( $field );
    $ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
    if ( ! in_array( $ext, $allowed ) ) {
        wp_send_json_error( 'File type .' . $ext . ' not allowed. Accepted: ' . implode( ', ', $allowed ) );
    }

    // Validate file size (10 MB max)
    $max_bytes = 10 * 1024 * 1024;
    if ( $file['size'] > $max_bytes ) {
        wp_send_json_error( 'File exceeds 10 MB limit.' );
    }

    // Always save a copy in the WP media library — that's the safety net.
    $wp_result = atp_wordpress_upload( $file, $field, $candidate );
    if ( is_wp_error( $wp_result ) ) {
        wp_send_json_error( $wp_result->get_error_message() );
    }

    // Optionally also mirror into Google Drive.
    $storage = get_option( 'atp_upload_storage', 'wordpress' );
    if ( $storage === 'google_drive' && atp_drive_is_configured() ) {
        $drive_result = atp_drive_upload( $file, $field, $candidate, $office );
        if ( is_wp_error( $drive_result ) ) {
            error_log( '[ATP Drive] mirror upload failed: ' . $drive_result->get_error_message() );
        } else {
            $wp_result['drive']      = $drive_result;
            $wp_result['drive_url']  = $drive_result['url']        ?? '';
            $wp_result['sub_folder'] = $drive_result['sub_folder'] ?? '';
            $wp_result['storage']    = 'wordpress+drive';
        }
    }

    wp_send_json_success( $wp_result );
}

/**
 * Upload to WordPress media library (default fallback).
 */
function atp_wordpress_upload( $file, $field, $candidate ) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    // Organize by candidate
    add_filter( 'upload_dir', function( $dirs ) use ( $candidate ) {
        $slug = sanitize_file_name( strtolower( str_replace( ' ', '-', $candidate ) ) );
        $dirs['subdir'] = '/atp-intake/' . $slug;
        $dirs['path']   = $dirs['basedir'] . $dirs['subdir'];
        $dirs['url']    = $dirs['baseurl'] . $dirs['subdir'];
        wp_mkdir_p( $dirs['path'] );
        return $dirs;
    } );

    $upload = wp_handle_upload( $file, [ 'test_form' => false ] );

    // Remove filter
    remove_all_filters( 'upload_dir' );

    if ( isset( $upload['error'] ) ) {
        return new WP_Error( 'upload_failed', $upload['error'] );
    }

    // Create attachment
    $attachment_id = wp_insert_attachment( [
        'post_title'     => sanitize_file_name( $file['name'] ),
        'post_mime_type' => $upload['type'],
        'post_status'    => 'inherit',
    ], $upload['file'] );

    if ( is_wp_error( $attachment_id ) ) {
        return $attachment_id;
    }

    $metadata = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
    wp_update_attachment_metadata( $attachment_id, $metadata );

    return [
        'url'    => $upload['url'],
        'id'     => $attachment_id,
        'name'   => $file['name'],
        'size'   => $file['size'],
        'storage' => 'wordpress',
    ];
}

/**
 * Mirror an uploaded file into the connected Google Drive folder.
 * Configuration comes from drive-client.php (OAuth user flow).
 *
 * @return array|WP_Error  ['url','id','sub_folder'] or error.
 */
function atp_drive_upload( $file, $field, $candidate, $office ) {
    $config           = get_option( 'atp_drive_config', [] );
    $parent_folder_id = $config['folder_id'] ?? '';
    if ( ! $parent_folder_id ) {
        return new WP_Error( 'atp_drive_no_folder', 'No destination folder picked.' );
    }

    $token = atp_drive_get_access_token();
    if ( is_wp_error( $token ) ) return $token;

    // Submission subfolder: YYYY-MM-DD_Candidate-Name_Office-Slug
    $date_prefix = gmdate( 'Y-m-d' );
    $cand_slug   = sanitize_file_name( str_replace( ' ', '-', $candidate ) );
    $office_slug = sanitize_file_name( str_replace( ' ', '-', $office ) );
    $folder_name = $date_prefix . '_' . ( $cand_slug ?: 'candidate' ) . ( $office_slug ? '_' . $office_slug : '' );

    $sub_folder_id = atp_drive_find_or_create_folder( $parent_folder_id, $folder_name, $token );
    if ( is_wp_error( $sub_folder_id ) ) return $sub_folder_id;

    $upload_name = sanitize_file_name( $field . '_' . $file['name'] );
    $mime        = ! empty( $file['type'] ) ? $file['type'] : 'application/octet-stream';

    $result = atp_drive_upload_file( $file['tmp_name'], $upload_name, $mime, $sub_folder_id, $token );
    if ( is_wp_error( $result ) ) return $result;

    return [
        'url'        => $result['webViewLink'] ?? '',
        'id'         => $result['id'],
        'name'       => $file['name'],
        'size'       => $file['size'],
        'sub_folder' => 'https://drive.google.com/drive/folders/' . rawurlencode( $sub_folder_id ),
    ];
}

/**
 * Get allowed file types per field.
 */
function atp_upload_allowed_types( $field ) {
    $types = [
        'headshot'          => [ 'jpg', 'jpeg', 'png', 'heic' ],
        'logo'              => [ 'jpg', 'jpeg', 'png', 'svg', 'pdf' ],
        'additional_photos' => [ 'jpg', 'jpeg', 'png', 'heic' ],
    ];
    return $types[ $field ] ?? [ 'jpg', 'jpeg', 'png' ];
}

/**
 * Allow HEIC and SVG uploads in WordPress.
 */
add_filter( 'upload_mimes', function( $mimes ) {
    $mimes['heic'] = 'image/heic';
    $mimes['svg']  = 'image/svg+xml';
    return $mimes;
} );

add_filter( 'wp_check_filetype_and_ext', function( $data, $file, $filename, $mimes ) {
    $ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
    if ( $ext === 'heic' ) {
        $data['ext']  = 'heic';
        $data['type'] = 'image/heic';
    }
    if ( $ext === 'svg' ) {
        $data['ext']  = 'svg';
        $data['type'] = 'image/svg+xml';
    }
    return $data;
}, 10, 4 );
