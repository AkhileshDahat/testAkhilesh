<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function EscapeData($v) {
	return htmlentities($v, ENT_QUOTES,'UTF-8');
}
?>