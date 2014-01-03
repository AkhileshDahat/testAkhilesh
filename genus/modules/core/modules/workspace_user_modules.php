<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );
//error_reporting(0);
function LoadTask() {

	$c="";

	$_SESSION['core_space_user_modules1']="yes";

	$c .= "<iframe src=modules/core/bin/core_space_user_modules.php width=100% height=600 frameborder=no>";

	return $c;

}
?>