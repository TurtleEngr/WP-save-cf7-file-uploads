WP-save-cf7-file-uploads
========================

![version](https://img.shields.io/badge/version-1.4.4-orange.svg)

![WordPress](https://img.shields.io/badge/WordPress-Compatible-blue.svg)

-   This plugin adds enhancements to \[Store file uploads for Contact
    Form
    7\](<https://wordpress.org/plugins/store-file-uploads-for-contact-form-7/>)
    by Mircea N. (version 1.3.0)

Enhancements
------------

1.  Normalize file names. File names can only contain letters in the
    set: \[a-zA-Z0-9.-\\\_\]. Convert any letters not in the set to a
    \"\\\_\".
2.  Only graphic files are allowed. Skipped files are logged.
3.  Avoid overwriting existing files, by appending \"\\~N~\" to base
    name.

Installation and Usage
----------------------

See: readme.txt for official install and usage directions.

### Development Installs

1.  Install and activate the Contact Form 7 plugin.
2.  Install and activate the Flamingo plugin.
3.  Go to:
    <https://moria.whyayh.com/rel/released/software/own/WP-save-cf7-uploads>
4.  Then download the save-cf7-file-uploads-VER.zip file you want.
5.  In WordPress, install with upload plugin, then activate.

In the \"Additional Settings\" tab for a contact form make sure
\"do~notstore~:\" is not \"true\"
