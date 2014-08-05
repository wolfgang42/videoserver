<?php
require_once '../includes.php';
$path = $_SERVER['PATH_INFO'];

if ($path == "") {
	redirect('/contents/', true);
} else if ($path == "/") {
	$query = $db->prepare('SELECT DISTINCT(key) FROM metadata ORDER BY key;');
	$query->execute();
	$listing = array();
	foreach ($query->fetchAll() as $key) {
		$listing[] = array(
			'type' => 'series',
			'name' => $key[0],
			'id' => $key[0].'/'
		);
	}
	twig_directory("Contents", '', 'contents', $listing);
} else if (substr($path, 0, 1) != '/') {
	// I don't know of any circumstances under which this could happen,
	// but I think that it would indicate something possibly confusing.
	// If it happens I don't want to accidentally chop off something significant.
	// Note: this can also happen in content.php; if you find yourself debugging a problem
	//       I would also fix it there.
	error(500, "Internal Error", "An unexpected URL was encountered; please ask your administrator to debug this. (Details: PATH_INFO does not start with a slash.)");
} else {
	$path = explode('/', substr($path, 1), 2); // Chop off leading slash and explode
	if (count($path) == 1) {
		redirect('/contents/'.$path[0].'/', true);
	} else if ($path[1] == '') {
		$key = $path[0];
		$query = $db->prepare('SELECT DISTINCT(value) FROM metadata WHERE key = ? ORDER BY value;');
		$query->execute(array($key));
		$listing = array();
		foreach ($query->fetchAll() as $value) {
			$listing[] = array(
				'type' => 'series',
				'name' => $value[0],
				'id' => $key.'/'.$value[0]
			);
		}
		twig_directory($key, $key, 'contents', $listing);
	} else {
		$key   = $path[0];
		$value = $path[1];
		
		$query = $db->prepare('SELECT id, title, is_series FROM videos WHERE id IN (SELECT video_id FROM metadata WHERE key = ? AND value = ?) ORDER BY title;');
		$query->execute(array($key, $value));
		
		$listing = array();
		foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $video) {
			$listing[] = array(
				'type' => $video['is_series']?'series':'video',
				'name' => $video['title'],
				'id' => $video['id']
			);
		}
		
		twig_directory("$key/$value", $key, 'library', $listing);
	}
}