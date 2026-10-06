=== Save CF7 File Uploads ===
Contributors: TurtleEngr
Tags: contact, form, library, file, upload
Requires at least: 4.9
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

This plugin is adds some enhancements to version 1.3.0 of
[Store file uploads for Contact Form 7](https://wordpress.org/plugins/store-file-uploads-for-contact-form-7/)
by Mircea N.

= Docs, Support and Source Code =

See: https://github.com/TurtleEngr/WP-save-cf7-file-uploads

Issues can be posted there. And the README.md will point to where you
can find the latest "development" versions.

= Privacy Notices =

This plugin, in itself, does not:

* track users;
* send any data to external servers;
* use cookies.

It does save files uploaded through Contact Form 7 in the WordPress
Media Library, which adds an attachment entry to the database for each
file. These files may contain personal data, and they are kept until
you delete them. Make sure the people who use your forms know this.

== Installation ==

1. Install and activate the Contact Form 7 plugin.
2. Install and activate the Flamingo plugin.
3. Install and activate this plugin from wordpress.org

In the "Additional Settings" tab for a contact form make sure
"do_not_store:" is not "true."  Now, image files uploaded with a
"Contact Form 7" form will appear in the Media Library.

== Changelog ==

= 1.4.1 =

Updated to follow the wordpress.org plugin guidelines.

= 1.4.0 =

1. Renamed to "Save CF7 File Uploads" for WordPress.org. The text
   domain is now 'save-cf7-file-uploads'.
2. A file is rejected if its extension is not an allowed image type.
3. The missing Contact Form 7 notice is translatable, escaped, and
   shown only to users who can activate plugins.
4. Added the "Requires Plugins: contact-form-7" header.

= 1.3.1 =

This is an enhanced version of "store-file-uploads-for-contact-form-7"
(version 1.3.0).

1. Normalize file names. File names can only contain letters in the
   set: [a-zA-Z0-9._-]. Convert any letters not in that set to a "_".
2. Only graphic files are allowed. Log skipped files.
3. Avoid overwriting existing files, by appending "_N" to base name.
