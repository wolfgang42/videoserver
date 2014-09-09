<?php
// DATA_DIR is not used outside of the below list of defines;
// it is a convenience constant.
// BASE_DIR is defined in inc_unauthenticated.php, and is the absolute path
// to the videoserver directory (the one that this file is in).
define('DATA_DIR',BASE_DIR.'/data/');

// Where the video files, etc, are stored.
define('CONTENT_DIR', DATA_DIR.'/content/');
// Where partially uploaded files are stored.
define('UPLOAD_DIR', DATA_DIR.'/upload/');
// CONTENT_DIR and UPLOAD_DIR should be on the same filesystem; otherwise,
// uploaded videos will have to be *copied* (rather than moved) across
// filesystem boundaries. (This is why they're both put in DATA_DIR by default).

// The location of the SQLITE database.
// Note that the *parent directory* must also be writable by the web server.
define('SQLITE_DB', DATA_DIR.'/content.sqlite');

// Where twig will cache template files.
// You can set this to false to disable caching altogether,
// however, this makes things slower and provides no advantage.
define('TWIG_CACHE_DIR', BASE_DIR.'/twig_cache/');

// Enable this if you are using basic authentication;
// see docs/auth_basic.md for details.
define('AUTH_BASIC', false);

// The username to use as a placeholder on the login form.
define('AUTH_FORM_EXAMPLEUSER', 'mrjdoe');
