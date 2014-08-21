<?php
require_once('upload_lib.php');
if (isset($_GET['uniqId'])) {
	$uniqId = upload_sanitize_identifier($_GET['uniqId']);
	foreach (glob(UPLOAD_DIR."tmp/$uniqId.*") as $delme) {
		unlink($delme);
	}
	echo '{status:'ok'}';
} else if (isset($_GET['fileId'])) {
	$fileId = upload_sanitize_filename($_GET['fileId']);
	unlink(UPLOAD_DIR."dest/$fileId");
	echo json_encode(array('status'=>'ok'));
} else {
	echo "No parameter specified for cancel action.";
	var_dump($_GET);
}
