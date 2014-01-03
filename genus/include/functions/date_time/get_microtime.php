<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function GetMicroTime() {
	list($usec, $sec) = explode(" ", microtime());
  return ((float)$usec + (float)$sec);
}
?>