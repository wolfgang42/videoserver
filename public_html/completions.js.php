<?php
require_once('../includes.php');
header("Content-Type: application/javascript");

echo 'define(function(require) {return{';

// Note the space in front of [None]. This insures that it will be sorted to the top of the list.
$seriesList = array(" [None]"=>0);
$query = $db->prepare('SELECT title, id FROM videos WHERE is_series;');
$query->execute();
while ($row = $query->fetch()) {
	$seriesList[$row[0]] = (int)$row[1];
}
echo 'series:'.json_encode($seriesList).',';

$metadataCompletions = array();
$query = $db->prepare('SELECT DISTINCT(key) FROM metadata;');
$subquery = $db->prepare('SELECT DISTINCT(value) FROM metadata WHERE key = ?;');
$query->execute();
while ($row = $query->fetch()) {
	$key = $row[0];
	$metadataCompletions[$key] = array();
	$subquery->execute(array($key));
	while ($subrow = $subquery->fetch()) {
		$metadataCompletions[$key][] = $subrow[0];
	}
}
echo 'metadata:'.json_encode($metadataCompletions); 

echo '}});';