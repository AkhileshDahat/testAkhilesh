<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function LoadTask() {

	$c="";
	// security
	$_SESSION['core_user_master1']="yes";

	$c .= "<iframe src=modules/core/bin/core_user_master.php width=100% height=600 frameborder=no>";

	return $c;
}
?>