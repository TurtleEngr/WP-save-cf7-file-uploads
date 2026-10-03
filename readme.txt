== Store file uploads for Contact Form 7 ===
Contributors: mirceatm, TurtleEngr
Tags: contact, form, library, file, upload
Requires at least: 4.9
Tested up to: 7.1.2
Stable tag: VERSION
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

When this is active, attachments sent trough Contact Form 7 variable
[file NAME] will be stored in your Media Library. This enhanced
version implements some of the Pro features.

This plug in is derived from:
https://plugins.svn.wordpress.org/store-file-uploads-for-contact-form-7/tags/1.3.0/

This plugin is not compatable with the
store-file-uploads-for-contact-form-7 plugin.

== Description ==

By default, [Contact Form7](https://wordpress.org/plugins/contact-form-7/)
does not keep data it sends trough it's contact forms.  While plugins
like [Flamingo](https://wordpress.org/plugins/flamingo/) save that
data, uploaded files are not added to Media Library.  This plugin will
save uploaded files to Media Library before email is sent by CF7.
This plugin will raise an event with the the full file path & name.
Subscribe to 'bar_create_attachment_file_name' filter to get and/or
update data before attachment is added to media library.

`
// The filter callback function.
function example_callback( $file_name ) {
    // (maybe) modify $file_name.
    return $file_name;
}
add_filter( 'bar_create_attachment_file_name', 'example_callback', 10, 1 );
`

Subscribe to 'bar_before_insert_attachment' filter to be able to
change attachment attributes: caption and description are
‘post_excerpt’ and ‘post_content’.  For other attributes, check
documentation for
[wp_insert_attachment](https://developer.wordpress.org/reference/functions/wp_insert_attachment/).

`
// The filter callback function.
function before_insert_attachment_callback( $attachment ) {
    // (maybe) modify $attachment array.
    return $attachment;
}
add_filter( 'bar_before_insert_attachment', 'before_insert_attachment_callback', 10, 1 );
`

Optionally, subscribe to
'bar_should_skip_save_attachment_to_media_library' filter to be able
to skip saving the attachment to media library: return true to skip,
false is the default behavior that saves the attachment to media
library. Filter 'bar_before_insert_attachment' will not be called if
skip was true.

`
// The filter callback function.
function skip_media_library_callback( $skip_save_to_media_library ) {
    // return true to skip saving to Media Library, false to save.
    return true;
}
add_filter( 'bar_should_skip_save_attachment_to_media_library', 'skip_media_library_callback', 10, 1 );
`

This plugin will send the final attachment id if you are interested in
getting other details, like attachment URL.  Listen to
'bar_create_attachment_id_generated' action.

`
// The action callback function.
function example_callback_id_generated( $attachment_id ) {
    // (maybe) do something with the args.
    $url = wp_get_attachment_url( $attachment_id );
}
add_action( 'bar_create_attachment_id_generated', 'example_callback_id_generated', 10, 1 );
`
= Docs & Support =

See: https://github.com/TurtleEngr/WP-store-cf7-file-uploads

= Privacy Notices =

With the default configuration, this plugin, in itself, does not:

* track users by stealth;
* write any user personal data to the database;
* send any data to external servers;
* use cookies.

It will, however store uploaded files trough Contact Form 7 in
WordPress Media Library.  Make sure your website users are aware of
this fact!!!

== Installation ==

1. Upload the entire  folder to the '/wp-content/plugins/' directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.

After that check Media Library for uploaded files, when you specify
files to be attached with Contact 7.

== Changelog ==

= 1.3.1 =

This is an enhanced version of store-file-uploads-for-contact-form-7
(version 1.3.0). It implements some of the pro features.

1. Normalize file names. File names can only contain letters in the
   set: [a-zA-Z0-9.-_]. Convert any letters not in that set to a "_".
2. Only graphic files are allowed. Log skipped files.
3. Avoid overwriting existing files, by appending "_N" to base name.
