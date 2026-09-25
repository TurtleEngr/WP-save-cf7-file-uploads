# WP-store-cf7-file-uploads

-   This is an enhanced version of 1.3.0. It implements some of the pro
    features.

1.  Normalize file names. File names can only contain letters in the
    set: \[a-zA-Z0-9.-\\\_\]. Convert any letters not in the set to a
    \"\\\_\".
2.  Only graphic files are allowed. Log skipped files.
3.  Avoid overwriting existing files, by appending \"\\~N~\" to base
    name.

-   Source, see:
    <https://github.com/TurtleEngr/WP-store-cf7-file-uploads>
