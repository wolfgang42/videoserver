<?php
require_once('../../inc_unauthenticated.php');
parse_str($_SERVER['REDIRECT_QUERY_STRING'],$REDIRECT_GET);
$twig->display('page/auth/login_form.twig', array(
	'loggedout' => isset($_COOKIE['loggedout'])
));
