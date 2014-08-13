<?php
require_once('../../../../includes.php');
foreach (array(UPLOAD_DIR,UPLOAD_DIR.'/dest',UPLOAD_DIR.'/tmp') as $dir)
	if (!file_exists($dir)) mkdir($dir);

// TODO: I don't actually recall why there are two slightly different requirements for identifiers and filenames...
function upload_sanitize_identifier($identifier) {
	return preg_replace('/[^0-9a-zA-Z_-]/im', '', $identifier);
}

function upload_sanitize_filename($filename) {
	return preg_replace('/[^0-9A-Za-z_.-]/im', '-', $filename);
}
