<?php
require_once('../../../../includes.php');
define('UPLOAD_CACHE', BASE_DIR.'/cache/upload/');

// TODO: I don't actually recall why there are two slightly different requirements for identifiers and filenames...
function upload_sanitize_identifier($identifier) {
	return preg_replace('/[^0-9a-zA-Z_-]/im', '', $identifier);
}

function upload_sanitize_filename($filename) {
	return preg_replace('/[^0-9A-Za-z_.-]/im', '-', $filename);
}