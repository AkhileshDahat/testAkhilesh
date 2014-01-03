<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/core/functions/browse/available_workspace_modules.php";

function LoadTask() {

	$c="";
	$db=$GLOBALS['db'];
	if (ISSET($_GET['subtask'])) {
		//echo "ok";
		require_once $GLOBALS['dr']."modules/core/classes/core_space_modules.php";
		$obj=new CoreSpaceModules;
		$obj->SetParameters($_GET['workspace_id'],"",$_GET['module_id']);
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

	$c.=AvailableWorkspaceModules($_GET['workspace_id']);

	return $c;
}
?>