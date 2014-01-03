<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/core/functions/browse/available_workspace_roles.php";

function LoadTask() {

	$c="";
	$db=$GLOBALS['db'];
	if (ISSET($_GET['subtask'])) {
		//echo "ok";
		require_once $GLOBALS['dr']."modules/core/classes/core_workspace_role_master.php";
		$obj=new CoreWorkspaceRoleMaster;
		$obj->SetParameters($_GET['workspace_id'],"",$_GET['role_id']);
		if ($_GET['subtask']=="install") {
			$result=$obj->Add();
		}
		else {
			$result=$obj->Remove();
		}
		if ($result) {
			$GLOBALS['errors']->SetAlert("Successfully added");
		}
		else {
			$GLOBALS['errors']->SetError($obj->ShowErrors());
		}
	}

	$c.=AvailableWorkspaceRoles($_GET['workspace_id']);

	return $c;
}
?>