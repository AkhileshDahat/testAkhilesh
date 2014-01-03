<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($dr."include/functions/design/curve_boxes.php");

if (IS_NUMERIC($GLOBALS['ui']->WorkspaceID()) && !IS_NUMERIC($GLOBALS['ui']->TeamspaceID())) {
	require_once($GLOBALS['dr']."include/functions/teamspace/user_teamspaces.php");
	require_once($GLOBALS['dr']."modules/workspace/functions/browse/non_enterprise_user_workspaces.php");

	echo "<table>\n";
		echo "<tr>\n";
			echo "<td width='90%'>\n";
			echo CurveBox(NonEnterpriseUserWorkspaces($GLOBALS['ui']->WorkspaceID(),$_SESSION['user_id']));
			echo "</td>\n";
			echo "<td width='150' valign='top'>\n";
			echo CurveBox(UserTeamspaces($GLOBALS['ui']->WorkspaceID(),$_SESSION['user_id']));
			echo "</td>\n";
		echo "</tr>\n";
	echo "</table>\n";
}
else {
	//echo "ok";
	require_once($dr."modules/workspace/functions/browse/non_enterprise_user_workspaces.php");
	echo CurveBox(NonEnterpriseUserWorkspaces($ui->WorkspaceID(),$_SESSION['user_id']));
}

?>