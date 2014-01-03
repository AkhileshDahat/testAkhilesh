<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/core/functions/browse/browse_workspaces.php";

function LoadTask() {

	$c="";
	$db=$GLOBALS['db'];
	if (ISSET($_GET['subtask'])) {
		if ($_GET['subtask']=="del") {
			require_once $GLOBALS['dr']."modules/core/classes/delete_workspace.php";
			$dw=new DeleteWorkspace;
			$dw->SetParameters($_GET['workspace_id'],$_SESSION['user_id']);
			$db->Begin();
			$result=$dw->Delete();
			if ($result) {
				$GLOBALS['errors']->SetAlert("Successfully deleted workspace");
				$db->Commit();
			}
			else {
				$GLOBALS['errors']->SetError($dw->ShowErrors());
				$db->Rollback();
			}
		}
	}

	$c.=BrowseWorkspaces($_SESSION['user_id']);

	return $c;
}
?>