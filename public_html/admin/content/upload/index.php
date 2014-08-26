<?php
require_once '../../../../includes.php';
require_once('upload_lib.php');
$uploaded = array();
if($dh = opendir(UPLOAD_DIR."dest")){
	while(($file=readdir($dh)) !== FALSE){
		if(substr($file, 0, 1) == '.' || !is_file(UPLOAD_DIR.'dest/' . $file)){
		 continue;
		}
		$uploaded[] = $file;
	}
	closedir($dh);
}
$twig->display('page/admin/content/upload.twig', array('activetab' => '*upload', 'uploaded' => $uploaded));
