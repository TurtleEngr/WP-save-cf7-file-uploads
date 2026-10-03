<?php
/*
Plugin Name: Store file uploads for Contact Form 7
Plugin URI: https://github.com/TurtleEngr/WP-store-cf7-file-uploads/tree/main
Description: Store all files uploded trough Contact Form 7 in your Media Library
Author: Mircea N., TurtleEngr
Text Domain: bar-store-cf7-uploads
Domain Path: /languages/
Version: VERSION
*/

add_action('plugins_loaded', function () {
    if (!class_exists('WPCF7')) {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p>';
            echo '<strong>Store file uploads for Contact Form 7</strong> requires <a href="https://wordpress.org/plugins/contact-form-7/" target="_blank">Contact Form 7</a> to be installed and active.';
            echo '</p></div>';
        });
        return;
    }
    add_action('wpcf7_before_send_mail', 'bar_on_before_cf7_send_mail');
});

// Replace chars not in [a-zA-Z0-9._-] with "_", then collapse repeated "_".
function bar_normalize_file_name($file_name)
{
    $file_name = preg_replace('/[^a-zA-Z0-9._-]/', '_', $file_name);
    return preg_replace('/_+/', '_', $file_name);
}

// If the file exists, insert "_N" before the extension, N = 1, 2, ... until unused.
function bar_unique_file_name($file_path)
{
    if (!file_exists($file_path)) {
        return $file_path;
    }
    $info = pathinfo($file_path);
    $ext = isset($info['extension']) ? '.' . $info['extension'] : '';
    $n = 1;
    do {
        $new_path = $info['dirname'] . '/' . $info['filename'] . '_' . $n . $ext;
        $n++;
    } while (file_exists($new_path));
    return $new_path;
}

// Returns the attachment metadata, or false if the file was not added to the Media Library.
function bar_create_attachment($filename)
{
    // Only image types allowed by WordPress, detected from the file content.
    $mime_type = wp_get_image_mime($filename);
    if (!$mime_type || !in_array($mime_type, get_allowed_mime_types(), true)) {
        error_log('Store file uploads for Contact Form 7: file type not allowed, not saved: ' . basename($filename));
        return false;
    }
    $wp_upload_dir = wp_upload_dir();

    $attachFileName = $wp_upload_dir['path'] . '/' . bar_normalize_file_name(basename($filename));
    $attachFileName = apply_filters('bar_create_attachment_file_name', $attachFileName);
    $attachFileName = bar_unique_file_name($attachFileName);
    if (!copy($filename, $attachFileName)) {
        error_log('Store file uploads for Contact Form 7: copy failed, not saved: ' . $attachFileName);
        return false;
    }

    $skip_save_to_media_library = apply_filters('bar_should_skip_save_attachment_to_media_library', false);
    if ($skip_save_to_media_library) {
        return false;
    }

    $attachment = array(
        'guid'           => str_replace($wp_upload_dir['basedir'], $wp_upload_dir['baseurl'], $attachFileName),
        'post_mime_type' => $mime_type,
        'post_title'     => preg_replace('/\.[^.]+$/', '', basename($attachFileName)),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );
    $attachment = apply_filters('bar_before_insert_attachment', $attachment);
    $attach_id = wp_insert_attachment($attachment, $attachFileName, 0, true);
    if (is_wp_error($attach_id) || !$attach_id) {
        $reason = is_wp_error($attach_id) ? ': ' . $attach_id->get_error_message() : '';
        error_log('Store file uploads for Contact Form 7: not added to Media Library, file removed: ' . $attachFileName . $reason);
        wp_delete_file($attachFileName);
        return false;
    }
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $attachFileName);
    wp_update_attachment_metadata($attach_id, $attach_data);

    do_action('bar_create_attachment_id_generated', $attach_id);
    return $attach_data;
}

function bar_on_before_cf7_send_mail()
{
    $submission = WPCF7_Submission::get_instance();
    if ($submission) {
        $uploaded_files = $submission->uploaded_files();
        if ($uploaded_files) {
            foreach ($uploaded_files as $filepath) {
                if (is_array($filepath)) {
                    foreach ($filepath as $value) {
                        bar_create_attachment($value);
                    }
                } else {
                    bar_create_attachment($filepath);
                }
            }
        }
    }
}
