<?php
require_once('../includes.php');
header("Content-type: text/plain");

foreach (array(CONTENT_DIR, UPLOAD_DIR, SQLITE_DB, TWIG_CACHE_DIR) as $file) {
	if (!is_writable($file)) {
		echo "Cannot write to ".(is_dir($file)?'directory':'file').": $file\n";
	}
}

if (!is_writable(dirname(SQLITE_DB))) {
	echo 'The folder containing the SQLite database ('.dirname(SQLITE_DB).') ';
	echo "cannot be written to; SQLite needs access to this directory.\n";
}

if (stat(CONTENT_DIR)[0] != stat(UPLOAD_DIR)[0]) {
	echo "CONTENT_DIR and UPLOAD_DIR are not on the same filesystem; ";
	echo "this will result in poor performance when uploading files.\n";
}

$query = $db->prepare('SELECT id, title FROM videos WHERE is_series AND parent_series IS NOT NULL');
$query->execute();
foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $value) {
	echo "Nested Series: id=".$value['id']." title=".$value['title']."\n";
}

$query = $db->prepare(
		'SELECT video_video.id, series_video.id,
				video_video.title, series_video.title,
				series_metadata.key
		FROM
			videos   AS series_video,
			videos   AS  video_video,
			metadata AS series_metadata,
			metadata AS  video_metadata
		WHERE
			video_video.parent_series IS NOT NULL
			-- Join clauses
			AND series_video.id = video_video.parent_series
			AND  video_video.id = video_metadata.video_id
			AND series_video.id = series_metadata.video_id
			-- Relevant conditions
			AND video_metadata.key = series_metadata.key;');
$query->execute();
foreach ($query->fetchAll() as $value) {
	echo "Video has same metadata key as parent: key=".$value[4]
			."\n\tvideo  = <id=".$value[0]." title=".$value[2].">"
			."\n\tseries = <id=".$value[1]." title=".$value[3].">\n";
}

$query = $db->prepare('SELECT filename FROM sources
		GROUP BY filename
		HAVING COUNT(filename) > 1;');
$subq = $db->prepare('SELECT videos.id, videos.title FROM videos, sources WHERE videos.id=sources.video_id AND sources.filename = ?;');
$query->execute();
foreach ($query->fetchAll() as $value) {
	echo "Multiple videos with same file: filename=".$value[0]."\n";
	$subq->execute(array($value[0]));
	foreach ($subq->fetchAll() as $subval) {
		echo "\tvideo  = <id=".$subval[0].' title='.$subval[1].">\n";
	}
}

$query = $db->prepare("SELECT filename FROM sources;");
$query->execute();
foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $i) {
	$file = $i['filename'];
	$path = CONTENT_DIR."/$file";
	if (!file_exists($path)) {
		echo "File does not exist: $file\n";
	} else if (!is_readable($path)) {
		echo "File exists but is unreadable: $file\n";
	}
}

$query = $db->prepare("SELECT COUNT(*) FROM sources WHERE filename  = ?;");
function read_dir_content($parent_dir){
	global $query;
	if ($parent_dir == 'lost+found') return;
	if ($parent_dir != '') {$parent_dir .= '/';}
	if ($handle = opendir(CONTENT_DIR.$parent_dir)) {
		while (false !== ($file = readdir($handle))) {
			if(in_array($file, array('.', '..'))) continue;
			if(is_dir(CONTENT_DIR.$parent_dir . $file)){
				read_dir_content($parent_dir . $file);
			} else if ($parent_dir.$file != '.gitignore') {
				$query->execute(array("$parent_dir$file"));
				if ($query->fetch(PDO::FETCH_ASSOC)['COUNT(*)'] != 1) {
					echo "File exists but is not in database: $parent_dir$file\n";
				}
			}
		}
		closedir($handle);
	} else {
		echo "Failed to open dir: $parent_dir\n";
	}
}
read_dir_content('');

$query = $db->prepare('PRAGMA foreign_key_check;');
$query->execute();
$fkc=false;
foreach ($query->fetchAll() as $violation) {
	$fkc=true;
	echo "Foreign key constraint violation: ".
		"<table=".$violation[0].
		" rowid=".$violation[1].
		" foreignTable=".$violation[2].
		" foreignKeyConstraint=".$violation[3].
		">\n";
}
if ($fkc) {
	echo "For details on foreign key constraint violations and PRAGMA foreign_key_check, see https://www.sqlite.org/pragma.html#pragma_foreign_key_check";
}

echo "Done.\n";
