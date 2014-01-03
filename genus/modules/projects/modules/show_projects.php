<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/projects/functions/browse/show_projects.php";

function LoadTask() {
	$ui=$GLOBALS['ui'];

	if (defined( '_VALID_MVH_MOBILE_' )) {
		return ShowProjects($ui->WorkspaceID(),$ui->TeamspaceID());
	}
	else {
		return CurveBox(ShowProjects($ui->WorkspaceID(),$ui->TeamspaceID()));
	}
}

?>