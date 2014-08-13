<?php
define('BASE_DIR',dirname(__FILE__));
require_once BASE_DIR.'/lib/autoload.php';
require_once BASE_DIR.'/config.php';

$twig = new Twig_Environment(
	new Twig_Loader_Filesystem(BASE_DIR.'/templates'),
	array(
		'debug' => true, # TODO change to false in production
		'cache' => TWIG_CACHE_DIR,
		'strict_variables' => true
	)
);

function error($code, $short, $message) {
	global $twig;
	header("HTTP/1.0 $code $short");
	$twig->display('error.twig',array('error' => array(
		'code' => $code,
		'short' => $short,
		'message' => $message
	)));
	die();
}

function redirect($location, $permanent) {
	# TODO check permanence, return HTML, etc.
	header("Location: $location");
	die();
}
