<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function YesNoImage($v) {
	if ($v=="y") {
		return "<img src='images/nuvola/16x16/actions/button_accept.png'>\n";
	}
	else {
		return "<img src='images/nuvola/16x16/actions/button_cancel.png'>\n";
	}
}
?>