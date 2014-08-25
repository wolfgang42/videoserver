<?php
require_once('upload_lib.php');
if (isset($_GET['uniqId'])) {
	$uniqId = upload_sanitize_identifier($_GET['uniqId']);
	foreach (glob(UPLOAD_DIR."tmp/$uniqId.*") as $delme) {
		unlink($delme);
	}
}
if (isset($_GET['fileName'])) {
	$fileName = upload_sanitize_filename($_GET['fileName']);
	unlink(UPLOAD_DIR."dest/$fileName");
	echo json_encode(array('status'=>'ok'));
} else {
	error (400, "fileId not specified.");
}
