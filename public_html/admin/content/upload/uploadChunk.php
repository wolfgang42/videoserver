<?php
require_once('upload_lib.php');
function response($code, $text, $message = null) {
	http_response_code($code);
	if ($message == null) {
		$message = "$code $text";
	}
	header("Content-Type: text/plain");
	echo $message;
	die();
}

function upload_details($REQ) {
	// Data from REQUEST
	$details = array (
		'chunkNumber' => (int)$REQ['resumableChunkNumber'],
		'chunkSize'   => (int)$REQ['resumableChunkSize'],
		'totalSize'   => (int)$REQ['resumableTotalSize'],
		'identifier'  => upload_sanitize_identifier($REQ['resumableIdentifier']),
		'filename'    => upload_sanitize_filename($REQ['resumableFilename']),
	);
	// Composite
	$details['tmpDir'] = UPLOAD_DIR.'tmp/';
	$details['chunkPrefix'] = $details['identifier'].'.part';
	$details['tmpFile'] = $details['tmpDir'].$details['chunkPrefix'].$details['chunkNumber'];
	$details['destinationFile'] = UPLOAD_DIR.'dest/'.$details['filename'];
	$details['fullFileName'] = $details['tmpDir'].$details['identifier'].".full";
	// How many chunks have we assembled?
	if (file_exists($details['fullFileName'])) {
		$fp = fopen($details['fullFileName'], "r");
		flock($fp, LOCK_SH);
		$details['fullChunksCount'] = filesize($details['fullFileName'])/$details['chunkSize'];
		fclose($fp);
	} else {
		$details['fullChunksCount'] = 0;
	}
	return $details;
}

function handleUpload($details) {
	// Save an uploaded chunk
	if (!isset($_FILES['file'])) {
		response(400, 'File Not Given');
	}
	$file = $_FILES['file'];
	if ($file['error'] != 0) {
		response(500, "Upload Failed", "Upload failed with code: ".$file['error']);
	}
	if (!move_uploaded_file($file['tmp_name'], $details['tmpFile'])) {
		response(500, "Upload Failed", "Error while moving uploaded file.");
	}
}

function reassembleFile($details) {
echo "Chunks: ".$details['fullChunksCount'];
	// Assemble additional chunks, if any
	if (($fp = fopen($details['fullFileName'], 'a')) !== false) {
		if (!flock($fp, LOCK_EX)) response(500, "Couldn't Acquire Lock");
		for ($i = $details['fullChunksCount'] + 1;; $i++) {
			$chunkFile = $details['tmpDir'].$details['chunkPrefix'].$i;
			if (file_exists($chunkFile)) {
				echo " Append chunk: $i";
				fwrite($fp, file_get_contents($chunkFile));
				unlink($chunkFile);
			} else {
				echo " Stop at chunk: $i";
				break; # No more chunks available
			}
		}
		fclose($fp);
	} else {
		response(500, "File Join Failed", "Couldn't open the destination file (".$details['fullFileName'].')');
	}
	
	// check that all the parts are present
    if (filesize($details['fullFileName']) == $details['totalSize']) {
		if (!rename($details['fullFileName'], $details['destinationFile'])) {
			response(500, "File Rename Failed", "Couldn't move to destination file (".$details['destinationFile'].')');
		}
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	// check if chunk has been uploaded yet
	$details = upload_details($_GET);
	
	if ($details['fullChunksCount'] >= $details['chunkNumber'] || file_exists($details['tmpFile'])) {
		response(200, "File Exists");
	} else {
		response(404, "Not Uploaded Yet");
	}
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$details = upload_details($_POST);
	handleUpload($details);
	reassembleFile($details);
} else {
	// Neither GET nor POST
	response(405, 'Method Not Allowed');
}
