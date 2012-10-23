<?php
/* This code will vary depending on how you store the files in your particular
 * application.  It is provided so that you will have a clear understanding of
 * exactly what we expect in these data structures.
 */

/**
 * Creates an array of the files that have been uploaded for a particular identifier.
 * @param string The unique identifier for the set of files we want to look at.
 * @return array Associative array with file name, file size key value pairs.
 */
function uploaded_files_array_builder($unique_identifier) {
	$path = preg_replace('/(.*)(\/demo.*)/', '${1}', $_SERVER['SCRIPT_FILENAME']);
	$path = $path.'/uploaded_files/'.$unique_identifier.'/';

	$return_arr = array();

	if (file_exists($path)&&$unique_identifier!=null) {
		if ($handle = opendir($path)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					$return_arr[$file] = filesize($path.$file);
				}
			}
			closedir($handle);
		}
	}

	return $return_arr;
}

/**
 * Determines the number of files that have been uploaded for a particular identifier.
 * @param string The unique identifier for the set of files we want to look at.
 * @return int Number of files on the server
 */
function files_on_server($unique_identifier) {
	$count = 0;
	$path = preg_replace('/(.*)(\/demo.*)/', '${1}', $_SERVER['SCRIPT_FILENAME']);
	$path = $path.'/uploaded_files/'.$unique_identifier.'/';

	if (file_exists($path)&&$unique_identifier!=null) {
		if ($handle = opendir($path)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					$count = $count + 1;
				}
			}
			closedir($handle);
		}
	}

	return $count;
}

function removeDir($sDir) {	
	if (is_dir($sDir)) {
		$sDir = rtrim($sDir, '/');
		$oDir = dir($sDir);
		while (($sFile = $oDir->read()) !== false) {
			if ($sFile != '.' && $sFile != '..') {
				(!is_link("$sDir/$sFile") && is_dir("$sDir/$sFile")) ? RemoveDir("$sDir/$sFile") : unlink("$sDir/$sFile");
			}
		}
		$oDir->close();
		rmdir($sDir);
		return true;
	}
	return false;
}

?>