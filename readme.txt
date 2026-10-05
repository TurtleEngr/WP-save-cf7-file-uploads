=== Store CF7 File Uploads ===
Contributors: mirceatm, TurtleEngr
Tags: contact, form, library, file, upload
Requires at least: 4.9
Tested up to: 7.1
Stable tag: VERSION
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Saves image files uploaded through Contact Form 7 to your Media Library before the email is sent.

== Description ==

By default, [Contact Form 7](https://wordpress.org/plugins/contact-form-7/)
does not keep the data sent through its contact forms. Plugins like
[Flamingo](https://wordpress.org/plugins/flamingo/) save that data,
but uploaded files are not added to the Media Library. This plugin
saves files uploaded with the Contact Form 7 `[file NAME]` field to
the Media Library before the email is sent.

Only image files are saved. The type is checked from the file
content, and the file extension must also be an allowed image type.
Other files are skipped, and a line is written to the PHP error log.
File names are reduced to the characters a-z, A-Z, 0-9, ".", "_" and
"-", and an existing file is never overwritten: "_1", "_2", and so on
is added to the name instead.

This plugin requires the Contact Form 7 plugin.

This plugin is derived from version 1.3.0 of
[Store file uploads for Contact Form 7](https://wordpress.org/plugins/store-file-uploads-for-contact-form-7/)
by Mircea N. It is not compatible with that plugin, so do not
activate both.

= Hooks =

This plugin passes the full file path and name to the
'storecf7_create_attachment_file_name' filter. Use it to read or change
the name before the attachment is added to the Media Library.

`
// The filter callback function.
function example_callback( $file_name ) {
    // (maybe) modify $file_name.
    return $file_name;
}
add_filter( 'storecf7_create_attachment_file_name', 'example_callback', 10, 1 );
`

Use the 'storecf7_before_insert_attachment' filter to change the
attachment attributes. The caption and description are 'post_excerpt'
and 'post_content'. For other attributes, see the documentation for
[wp_insert_attachment](https://developer.wordpress.org/reference/functions/wp_insert_attachment/).

`
// The filter callback function.
function before_insert_attachment_callback( $attachment ) {
    // (maybe) modify $attachment array.
    return $attachment;
}
add_filter( 'storecf7_before_insert_attachment', 'before_insert_attachment_callback', 10, 1 );
`

Optionally, use the
'storecf7_should_skip_save_attachment_to_media_library' filter to skip
adding the file to the Media Library. Return true to skip. The default,
false, adds it. When skip is true, the file is still copied to the
uploads folder, and the 'storecf7_before_insert_attachment' filter is
not called.

`
// The filter callback function.
function skip_media_library_callback( $skip_save_to_media_library ) {
    // return true to skip saving to Media Library, false to save.
    return true;
}
add_filter( 'storecf7_should_skip_save_attachment_to_media_library', 'skip_media_library_callback', 10, 1 );
`

The 'storecf7_create_attachment_id_generated' action receives the new
attachment ID, so you can get other details, such as the attachment
URL.

`
// The action callback function.
function example_callback_id_generated( $attachment_id ) {
    // (maybe) do something with the args.
    $url = wp_get_attachment_url( $attachment_id );
}
add_action( 'storecf7_create_attachment_id_generated', 'example_callback_id_generated', 10, 1 );
`

= Docs, Support and Source Code =

See: https://github.com/TurtleEngr/WP-store-cf7-file-uploads

= Privacy Notices =

This plugin, in itself, does not:

* track users;
* send any data to external servers;
* use cookies.

It does store files uploaded through Contact Form 7 in the WordPress
Media Library, which adds an attachment entry to the database for each
file. These files may contain personal data, and they are kept until
you delete them. Make sure the people who use your forms know this.

== Installation ==

1. Install and activate the Contact Form 7 plugin.
1. Upload the entire 'store-cf7-file-uploads' folder to the '/wp-content/plugins/' directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.

After that, image files uploaded with a Contact Form 7 form appear in
the Media Library.

== Changelog ==

= 1.4.0 =

1. Renamed to "Store CF7 File Uploads" for WordPress.org. The text
   domain is now 'store-cf7-file-uploads'.
2. The hook prefix changed from 'bar_' to 'storecf7_'. Rename any
   callbacks that used the old hook names.
3. A file is also rejected if its extension is not an allowed image
   type.
4. The missing Contact Form 7 notice is translatable, escaped, and
   shown only to users who can activate plugins.
5. Added the "Requires Plugins: contact-form-7" header.

= 1.3.1 =

This is an enhanced version of store-file-uploads-for-contact-form-7
(version 1.3.0).

1. Normalize file names. File names can only contain letters in the
   set: [a-zA-Z0-9._-]. Convert any letters not in that set to a "_".
2. Only graphic files are allowed. Log skipped files.
3. Avoid overwriting existing files, by appending "_N" to base name.
