<?php
include("../../../includes.php");

if (isset($_GET['id'])) {
	$id = (int)$_GET['id'];
} else {
	error(400, "Bad Request", "No id specified");
}

function more_delete($id) {
	global $db;
	$query = $db->prepare("SELECT id, title FROM videos WHERE parent_series = ?;");
	$query->execute(array($id));
	$more_delete = array();
	foreach($query->fetchAll(PDO::FETCH_ASSOC) as $row) {
		$more_delete[$row['id']] = $row['title'];
	}
	return $more_delete;
}

function delete($id) {
	global $db;
	foreach (more_delete($id) as $more_id => $more_title) {
		delete($more_id);
	}
	foreach(array('sources', 'tracks') as $table) {
		$query = $db->prepare("SELECT filename FROM $table WHERE video_id = ?;");
		$query->execute(array($id));
		foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row) {
			unlink(CONTENT_DIR.'/'.$row['filename']);
		}
	}
	foreach (array('metadata', 'sources', 'tracks') as $table) {
		$db->prepare("DELETE FROM $table WHERE video_id = ?;")
			->execute(array($id));
	}
	$db->prepare("DELETE from videos WHERE id = ?;")
		->execute(array($id));
}

$query = $db->prepare("SELECT COUNT(*),* FROM videos WHERE id = ?;");
$query->execute(array($id));
$video = $query->fetch(PDO::FETCH_ASSOC);
if ($video['COUNT(*)'] == 1) {
	if ($video['is_series']) {
		$more_delete = more_delete($id);
	} else {
		$more_delete = array();
	}
	if (!isset($_POST['confirm'])) {
		$twig->display('admin/content/delete_confirm.twig', array(
			'id' => $id,
			'title' => $video['title'],
			'is_series' => $video['is_series'],
			'more_delete' => $more_delete,
		));
	} else {
		$db->beginTransaction();
		delete($id);
		$db->commit();
		redirect('/library/', true);
	}
} else {
	error(404, "Not Found", "The specified ID is invalid.");
}
