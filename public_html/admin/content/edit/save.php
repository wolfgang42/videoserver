<?php
include("../../../../includes.php");
$db->beginTransaction();
$series = array();
if (isset($_POST['*title'])) {
	$title = $_POST['*title'];
} else {
	error(400, "Bad Request", "No title");
}

if (isset($_GET['new'])) {
	if ($_GET['new'] != 'series' && $_GET['new'] != 'video')
		error(400, "Bad Request", "Invalid new type");
	
	$q = $db->prepare("SELECT COUNT(*) FROM videos WHERE title = ? AND is_series = ?;");
	$q ->execute(array($title, $_GET['new'] == 'series'));
	if ($q->fetch()[0] != 0)
		error (400, "Bad Request", "A ".$_GET['new']." with that title already exists.");
	
	$db->prepare("INSERT INTO videos (title, parent_series, is_series) VALUES (?, ?, ?);")
			->execute(array($title, null, $_GET['new'] == 'series'));
	$id = $db->lastInsertId();
	if ($_GET['new'] == 'video') {
		// Save the mp4 file
		if (!isset($_POST['*uploadedFileName']))
			error(400, "Bad Request", "Uploaded file was not specified.");
		require_once('../upload/upload_lib.php');
		$fileId = upload_sanitize_filename($_POST['*uploadedFileName']);
		if (!rename (UPLOAD_DIR."/dest/$fileId", CONTENT_DIR."/$fileId")) {
			error(500, "Internal Server Error", "Could not move uploaded file to final location.");
		}
		// Insert into database
		$db->prepare("INSERT INTO sources (video_id, filename, type) VALUES (?, ?, 'video/mp4');")
			-> execute(array($id, $fileId));
	}
} else if (isset($_GET['id'])) {
	$id = (int)$_GET['id'];
} else {
	error(400, "Bad Request", "No id or new specified");
}

$query = $db->prepare("SELECT COUNT(*),* FROM videos WHERE id = ?;");
$query->execute(array($id));
$row = $query->fetch(PDO::FETCH_ASSOC);
if ($row['COUNT(*)'] == 1) {
	if ($row['is_series']) {
		$parent_series = null;
	} else {
		if (isset($_POST['*series']) && $_POST['*series'] != 0) {
			$query = $db->prepare("SELECT COUNT(*) FROM videos WHERE id = ? AND is_series = 1;");
			$query->execute(array($_POST['*series']));
			if ($query->fetch()[0] == 1) {
				$parent_series = $_POST['*series'];
			} else {
				error(400, "Bad Request", "Unknown series specified");
			}
		} else {
			$parent_series = null;
		}
	}
	$db->prepare("UPDATE videos SET title = ?, parent_series = ? WHERE id = ?;")
		->execute(array($title, $parent_series, $id));
	$db->prepare("DELETE FROM metadata WHERE video_id = ?;")
		->execute(array($id));
	$query = $db->prepare("INSERT INTO metadata (video_id, key, value) VALUES (?, ?, ?);");
	foreach ($_POST as $key => $value) {
		if (substr($key, 0, 1) != '*') {
			$query->execute(array($id, $key, $value));
		}
	}
} else {
	error(404, "Not Found", "The specified ID is invalid.");
}

$db->commit();

if (!isset($_GET['return'])) {
	error(200, "OK", "The data was saved successfully but no return method was specified.");
} else if ($_GET['return'] == 'ok') {
	echo json_encode(array(
		'status' => 'ok',
		'id' => $id,
	));
} else if ($_GET['return'] == 'callback') {
	echo "<script>window.parent.EditorCompleteCallback('$title', $id);</script>";
} else if ($_GET['return'] == 'library') {
	redirect('/library/'.($parent_series==null?'':$parent_series).'#video:'.$id, false);
} else {
	error(200, "OK",  "The data was saved successfully but the specified return method is unknown.");
}
