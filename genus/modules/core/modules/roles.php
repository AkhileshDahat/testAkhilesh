<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/core/classes/role_id.php";
require_once $GLOBALS['dr']."classes/design/tab_boxes.php";

function LoadTask() {

	$c="";
	$_SESSION['core_role_master1']="yes";

	$c .= "<iframe src=modules/core/bin/core_role_master.php width=100% height=600 frameborder=no>";

	return $c;
}
?>