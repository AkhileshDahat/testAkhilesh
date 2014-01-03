<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/workspace/functions/browse/available_workspace_modules.php";

function LoadTask() {

	$c="";

	/* SUBTASKS */
	if (ISSET($_GET['subtask'])) {
		if ($_GET['subtask']=="install") {
			require_once $GLOBALS['dr']."modules/workspace/classes/new_workspace.php";
			$module_id=$_GET['module_id'];
			$workspace_id=$_GET['workspace_id'];
			$nw=new NewWorkspace();
			$result=$nw->WorkspaceModules($workspace_id,$_SESSION['user_id'],$module_id);
			if ($result) {
				$c.="Success";
				$c.=Alert(83);
			}
			else {
				$c.="Failure";
				$c.=Alert(82);
			}
		}
	}

	$c.=CurveBox(AvailableWorkspaceModules($_GET['workspace_id']));

	return $c;
}
?>