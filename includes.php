<?php
require_once dirname(__file__).'/inc_unauthenticated.php';
define('CONTENT_DIR',BASE_DIR.'/content/');

session_start();
if (!isset($_SESSION['username'])) {
	redirect('/auth/login?redirect='.urlencode($_SERVER['REQUEST_URI']));
}

function twig_directory($title, $activetab, $template, $listing) {
	global $twig, $db;
	$twig->addFilter(new Twig_SimpleFilter('disksi', function ($bytes) {
		$symbols = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
		$exp = floor(log($bytes)/log(1024));

		return sprintf('%.2f '.$symbols[$exp], ($bytes/pow(1024, floor($exp))));
	}));
	
	$tabs = array("Alphabetical");
	$query = $db->prepare("SELECT DISTINCT(key) FROM metadata ORDER BY key;");
	$query->execute();
	foreach ($query->fetchAll() as $tab) {
		$tabs[] = $tab[0];
	}
	
	$twig->display($template.'.twig', array(
		'title' => $title,
		'listing' => $listing,
		'space' => array(
			'free'  => disk_free_space(CONTENT_DIR),
			'total' => disk_total_space(CONTENT_DIR)
		),
		'tabs' => $tabs,
		'activetab' => $activetab,
	));
}

$db = new PDO("sqlite:".BASE_DIR.'/content.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->query("PRAGMA foreign_keys=ON;");
