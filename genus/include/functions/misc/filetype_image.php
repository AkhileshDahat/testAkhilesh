<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function FiletypeImage($v,$title="") {

	$wb=$GLOBALS['wb'];

	if (file_exists($GLOBALS['dr']."images/filetypes/".$v.".gif")) {
		return "<img src='".$wb."images/filetypes/".$v.".gif' border='0' title='".$title."'>\n";
	}
	else {
		return "<img src='".$wb."images/filetypes/other.gif'' border='0' title='".$title."'>\n";
	}
}
?>