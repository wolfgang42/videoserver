<?php

session_start();
session_regenerate_id(true); // Prevent session fixing
$_SESSION['username']    = $_SERVER['AUTHENTICATE_SAMACCOUNTNAME'];
$_SESSION['displayname'] = $_SERVER['AUTHENTICATE_DISPLAYNAME'];
// supposedly_admin puts emphasis on *supposedly* because this should never be used
// for access control; let apache and mod_ldap do that instead.
$_SESSION['supposedly_admin'] = true;
session_write_close();

require_once('../../inc_unauthenticated.php');
if (isset($_GET['redirect'])) {
	if (substr($_GET['redirect'], 0, 1) == '/') {
		redirect($_GET['redirect']);
	} else {
		redirect('/'.$_GET['redirect'], false);
	}
} else {
	redirect('/', false);
}
