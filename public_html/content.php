<?php
require_once '../includes.php';
$path = $_SERVER['PATH_INFO'];
if ($path == '' || $path == '/') {
	redirect('/', true);
} else if (substr($path, 0, 1) != '/') {
	// I don't know of any circumstances under which this could happen,
	// but I think that it would indicate something possibly confusing.
	// If it happens I don't want to accidentally chop off something significant.
	// Note: this can also happen in library.php; if you find yourself debugging a problem
	//       I would also fix it there.
	error(500, "Internal Error", "An unexpected URL was encountered; please ask your administrator to debug this. (Details: PATH_INFO does not start with a slash.)");
} else {
	$filename = substr($path, 1); // Chop off leading slash
	$query = $db->prepare("SELECT COUNT(*),* FROM sources WHERE filename = ?;");
	$query->execute(array($filename));
	$info = $query->fetch(PDO::FETCH_ASSOC);
	if ($info['COUNT(*)'] == 0) {
		// Note: in the *database* - we don't care if it's in the filesystem yet.
		error(404, "Not In Database", "The requested video is not in the database");
	} else if ($info['COUNT(*)'] == 1) {
		# TODO check if the user is authorized to view this video
		# Send it!
		header("Content-Type: ".$info['type']);
		header("X-Sendfile: ".CONTENT_DIR.'/'.$path);
		echo "If you can see this, the server needs to have mod_xsendfile enabled.";
	} else {
		// CAN'T HAPPEN
		error(500, "Duplicate files", "There seem to be duplicate file entries in the database, which shouldn't be possible. Please contact the system administrator to sort this out.");
	}
}
