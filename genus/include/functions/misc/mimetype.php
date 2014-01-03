<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function Mimetype($f) {
	if (file_exists($f)) {
		$extension=strtolower(substr($f,-3));
		if ($extension=="jpg") { return "image/jpeg"; }
		else { return "application/octet-stream"; }
	}
	else {
	return False;
	}
}
?>