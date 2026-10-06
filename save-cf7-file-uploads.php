<?php
/*
Plugin Name: Save CF7 File Uploads
Plugin URI: https://github.com/TurtleEngr/WP-save-cf7-file-uploads/
Description: Save image files uploaded with Contact Form 7 to your Media Library.
Version: VERSION
Requires at least: 4.9
Requires Plugins: contact-form-7, flamingo
Author: turtleengr
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: save-cf7-file-uploads
*/

if (!defined('ABSPATH')) {
    exit;
}

add_action('plugins_loaded', function () {
    if (!class_exists('WPCF7')) {
        add_action('admin_notices', function () {
            if (!current_user_can('activate_plugins')) {
                return;
            }
            echo '<div class="notice notice-error"><p>';
            printf(
                /* translators:
                 * 1: this plugin's name,
                 * 2: link to the Contact Form 7 plugin page
                 */
                esc_html__('%1$s requires %2$s to be installed and active.', 'save-cf7-file-uploads'),
                '<strong>Save CF7 File Uploads</strong>',
                '<a href="' . esc_url('https://wordpress.org/plugins/contact-form-7/') . '" target="_blank">Contact Form 7</a>'
            );
            echo '</p></div>';
        });
        return;
    }
    add_action('wpcf7_before_send_mail', 'savecf7_on_before_cf7_send_mail');
});

/*
 * Replace chars not in [a-zA-Z0-9._-] with "_", then collapse
 *  repeated "_".
 */
function savecf7_normalize_file_name($file_name)
{
    $file_name = preg_replace('/[^a-zA-Z0-9._-]/', '_', $file_name);
    return preg_replace('/_+/', '_', $file_name);
}

/*
 * If the file exists, insert "_N" before the extension, N = 1, 2,
 *  ... until unused.
 */
function savecf7_unique_file_name($file_path)
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

/*
 * Returns the attachment metadata, or false if the file was not added
 * to the Media Library.
 */
function savecf7_create_attachment($filename)
{
    /*
     * The extension must also be an allowed image type, so a file is
     * never saved with an extension the web server would run or serve
     * as a page.
     */
    $ext_type = wp_check_filetype(basename($filename));
    if (!$ext_type['type'] || strpos($ext_type['type'], 'image/') !== 0) {
        error_log('Save CF7 File Uploads: file extension not allowed, not saved: ' . basename($filename));
        return false;
    }
    // Only image types allowed by WordPress, detected from the file content.
    $mime_type = wp_get_image_mime($filename);
    if (!$mime_type || !in_array($mime_type, get_allowed_mime_types(), true)) {
        error_log('Save CF7 File Uploads: file type not allowed, not saved: ' . basename($filename));
        return false;
    }
    $wp_upload_dir = wp_upload_dir();

    $attachFileName = $wp_upload_dir['path'] . '/' . savecf7_normalize_file_name(basename($filename));
    $attachFileName = apply_filters('savecf7_create_attachment_file_name', $attachFileName);
    $attachFileName = savecf7_unique_file_name($attachFileName);
    if (!copy($filename, $attachFileName)) {
        error_log('Save CF7 File Uploads: copy failed, not saved: ' . $attachFileName);
        return false;
    }

    $skip_save_to_media_library = apply_filters('savecf7_should_skip_save_attachment_to_media_library', false);
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
    $attachment = apply_filters('savecf7_before_insert_attachment', $attachment);
    $attach_id = wp_insert_attachment($attachment, $attachFileName, 0, true);
    if (is_wp_error($attach_id) || !$attach_id) {
        $reason = is_wp_error($attach_id) ? ': ' . $attach_id->get_error_message() : '';
        error_log('Save CF7 File Uploads: not added to Media Library, file removed: ' . $attachFileName . $reason);
        wp_delete_file($attachFileName);
        return false;
    }
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $attachFileName);
    wp_update_attachment_metadata($attach_id, $attach_data);

    do_action('savecf7_create_attachment_id_generated', $attach_id);
    return $attach_data;
}

function savecf7_on_before_cf7_send_mail()
{
    $submission = WPCF7_Submission::get_instance();
    if ($submission) {
        $uploaded_files = $submission->uploaded_files();
        if ($uploaded_files) {
            foreach ($uploaded_files as $filepath) {
                if (is_array($filepath)) {
                    foreach ($filepath as $value) {
                        savecf7_create_attachment($value);
                    }
                } else {
                    savecf7_create_attachment($filepath);
                }
            }
        }
    }
}
