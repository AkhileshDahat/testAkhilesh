<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function InitCap($v) {
	return STRTOUPPER(SUBSTR($v, 0, 1)).SUBSTR($v, 1);
}
?>