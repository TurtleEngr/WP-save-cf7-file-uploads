=== Save CF7 File Uploads ===
Contributors: TurtleEngr
Tags: contact, form, library, file, upload
Requires at least: 6.0
Tested up to: 7.1
Stable tag: VERSION
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Saves image files uploaded through Contact Form 7 to your Media
Library before the email is sent.

== Description ==

By default, [Contact Form 7](https://wordpress.org/plugins/contact-form-7/)
does not keep the data sent through its contact forms. Plugins like
[Flamingo](https://wordpress.org/plugins/flamingo/) saves the form
data, but uploaded files are not added to the Media Library. This
plugin saves files uploaded with the Contact Form 7 `[file NAME]`
field to the Media Library before the email is sent.

Only image files are saved. The type is checked from the file
content, and the file extension must also be an allowed image type.
Other files are skipped, and a line is written to the PHP error log.

File names are reduced to the characters a-z, A-Z, 0-9, ".", "_" and
"-", and existing files are never overwritten: "_1", "_2", and so on
is added to the name instead.

This plugin requires the "Contact Form 7" and "Flamingo" plugins.

This plugin adds some enhancements to version 1.3.0 of
[Store file uploads for Contact Form 7](https://wordpress.org/plugins/store-file-uploads-for-contact-form-7/)
by Mircea N.

== Installation ==

1. Install and activate the Contact Form 7 plugin.
1. Install and activate the Flamingo plugin.
1. Install and activate this plugin from wordpress.org

In the "Additional Settings" tab for a contact form make sure
"do_not_store:" is not "true."  Now, image files uploaded with a
"Contact Form 7" form will appear in the Media Library.

== Frequently Asked Questions ==

= Where can I get more documentation =

See: https://github.com/TurtleEngr/WP-save-cf7-file-uploads Issues

= Where can I report bug or feature requests? =

Issues and feature requests can be posted there.

= Where can I find newer versions? =

The latest "stable version" can be found as wordpress.org.

See: https://github.com/TurtleEngr/WP-save-cf7-file-uploads#development-installs
For where to find the latest development versions.

== Changelog ==

= 1.4.3 =

Updated to follow the wordpress.org plugin guidelines.

= 1.4.0 =

1. Renamed to "Save CF7 File Uploads" for WordPress.org. The text
   domain is now 'save-cf7-file-uploads'.
2. A file is rejected if its extension is not an allowed image type.
3. The missing Contact Form 7 notice is translatable, escaped, and
   shown only to users who can activate plugins.
4. Added the "Requires Plugins: contact-form-7" header.

= 1.3.1 =

Enhance "store-file-uploads-for-contact-form-7" (version 1.3.0).

1. Normalize file names. File names can only contain letters in the
   set: [a-zA-Z0-9._-]. Convert any letters not in that set to a "_".
2. Only graphic files are allowed. Log skipped files.
3. Avoid overwriting existing files, by appending "_N" to base name.
