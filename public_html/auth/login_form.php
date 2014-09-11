<?php
require_once('../../inc_unauthenticated.php');
$twig->display('page/auth/login_form.twig', array(
	'exampleuser' => AUTH_FORM_EXAMPLEUSER,
	'loggedout' => isset($_COOKIE['loggedout'])
));
