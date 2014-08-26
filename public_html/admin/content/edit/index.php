<?php
require_once '../../../../includes.php';

if (isset($_GET['new'])) {
	if ($_GET['new'] != 'series')
		error(400, "Bad Request", "Invalid new type");
	$video = array(
		'title' => '',
		'is_series' => true);
	$metadata = array();
	$id = 'new';
} else if (isset($_GET['id'])) {
	$id = (int)$_GET['id'];
	$query = $db->prepare("SELECT COUNT(*),* FROM videos WHERE id = ?;");
	$query->execute(array($id));
	$video = $query->fetch(PDO::FETCH_ASSOC);
	if ($video['COUNT(*)'] == 1) {
		$metadata = array();
		$query = $db->prepare("SELECT key, value FROM metadata WHERE video_id = ?;");
		$query->execute(array($id));
		foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $datum) {
			$metadata[$datum['key']] = $datum['value'];
		}
	} else {
		error(404, "Not Found", "The requested video does not seem to exist.");
	}
} else {
	error(400, "Bad Request", "No id or new specified");
}

if (isset($_GET['return'])) {
	if ($_GET['return'] == 'callback' || $_GET['return'] == 'library') {
		$return = $_GET['return'];
	} else {
		error(400, "Bad Request", "Invalid return method.");
	}
} else { // Default
	$return = 'library';
}

$twig->display('page/admin/content/edit.twig', array(
		'video' => $video,
		'metadata' => $metadata,
		'id' => $id,
		'return' => $return,
	));

