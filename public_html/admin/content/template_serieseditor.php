<?php
include("../../../includes.php");

$twig->display('include_videoeditor.twig', array(
	'video' => array('is_series'=>true),
	'id' => 'new',
	'return' => 'callback'));