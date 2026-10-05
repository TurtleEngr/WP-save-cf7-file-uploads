# WP-store-cf7-file-uploads

![version](https://img.shields.io/badge/version-1.4.0-orange.svg)

![WordPress](https://img.shields.io/badge/WordPress-Compatible-blue.svg)

-   This is an enhanced version \"store-file-uploads-for-contact-form-7
    ver 1.3.0.\" It implements some of the Pro features.

-   Source, see:
    <https://github.com/TurtleEngr/WP-store-file-uploads-for-contact-form-7/tree/tags-1.3.0>

## Enhancements

1.  Normalize file names. File names can only contain letters in the
    set: \[a-zA-Z0-9.-\\\_\]. Convert any letters not in the set to a
    \"\\\_\".
2.  Only graphic files are allowed. Skipped files are logged.
3.  Avoid overwriting existing files, by appending \"\\~N~\" to base
    name.

## Installation

See: readme.txt
