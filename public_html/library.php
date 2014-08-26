<?php
require_once '../includes.php';
$path = $_SERVER['PATH_INFO'];

function fetch_by_id($table, $id) {
	global $db;
	$query = $db->prepare("SELECT * FROM $table WHERE video_id = ?;");
	$query->execute(array($id));
	return $query->fetchAll(PDO::FETCH_ASSOC);
}

function show_directory($series) {
	global $db;
	if ($series == null) {
		$title = "All Videos";
		$query = $db->prepare("SELECT * FROM videos WHERE parent_series IS null ORDER BY title;");
		$query->execute();
	} else {
		if (!$series['is_series']) { // CAN'T HAPPEN: Sanity check
			error(500, "Internal Server Error", "show_directory() was called with a video, not a series!");
		}
		$title = $series['title'];
		$query = $db->prepare("SELECT * FROM videos WHERE parent_series = ? ORDER BY title;");
		$query->execute(array($series['id']));
	}
	
	$listing = array();
	foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $video) {
		$listing[] = array(
			'type' => $video['is_series']?'series':'video',
			'name' => $video['title'],
			'id' => $video['id']
		);
	}
	
	twig_directory($title, 'Alphabetical', 'library', $listing);
}

if ($path == "") {
	redirect('/library/', true);
} else if ($path == "/") {
	show_directory(null);
} else if (substr($path, 0, 1) != '/') {
	// I don't know of any circumstances under which this could happen,
	// but I think that it would indicate something possibly confusing.
	// If it happens I don't want to accidentally chop off something significant.
	// Note: this can also happen in content.php; if you find yourself debugging a problem
	//       I would also fix it there.
	error(500, "Internal Error", "An unexpected URL was encountered; please ask your administrator to debug this. (Details: PATH_INFO does not start with a slash.)");
} else {
	$id = (int)substr($path, 1); // Chop off leading slash
	if ($id == 0) {
		error(404, "Not Found", "The given ID does not seem to be valid.");
	} else if ($path != '/'.$id) { // Normalize URLs
		redirect('/library/'.$id, true);
	} else {
		$query = $db->prepare("SELECT COUNT(*),* FROM videos WHERE id = ?;");
		$query->execute(array($id));
		$row = $query->fetch(PDO::FETCH_ASSOC);
		if ($row['COUNT(*)'] == 1) {
			if ($row['is_series']) {
				show_directory($row);
			} else { // is video
			$twig->display('page/video.twig', array (
					'title' => $row['title'],
					//TODO 'series' =>
					'metadata' => fetch_by_id('metadata', $id),
					'sources'  => fetch_by_id('sources', $id),
					'tracks'  => fetch_by_id('tracks', $id),
				));
			}
		} else {
			error(404, "Not Found", "That video does not exist.");
		}
	}
}
die();

$listing = array();
foreach (scandir($path) as $file) {
	if ($file != '.' && $file != '..' && is_dir("$path/$file")) {
		if (is_file("$path/$file/$file.json")) {
			$listing[] = array('type' => 'movie',   'name' => $file, 'link' => "$urlprefix$file");
		} else {
			$listing[] = array('type' => 'dir',     'name' => $file, 'link' => "$urlprefix$file/");
		}
	}
}
