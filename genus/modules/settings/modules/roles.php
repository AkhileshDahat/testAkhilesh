<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

function LoadTask() {

	$c="";

	$c .= "<iframe src=modules/settings/bin/core_workspace_role_master.php width=100% height=600 frameborder=no>";

	return $c;
}
?>