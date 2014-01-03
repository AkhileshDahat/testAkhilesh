<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."modules/core/classes/workspace_users.php");
require_once($GLOBALS['dr']."modules/hrms/functions/browse/user_list.php");

function LoadTask() {

	$c="";

	$teamspace_id=$GLOBALS['ui']->TeamspaceID();

	if (ISSET($_GET['subtask'])) {
		if ($_GET['subtask']=="delete_user" && IS_NUMERIC($_GET['user_id'])) {
			$c.="Removing...<br>";
			$obj_wu=new WorkspaceUsers;
			$result=$obj_wu->SetParameters($GLOBALS['workspace_id'],$_GET['user_id']);
			$result_delete=$obj_wu->DeleteUser();
			if ($result && $result_delete) {
				$c.="Success<br>";
			}
			else {
				$c.="Failed<br>";
				$c.=$obj_wu->ShowErrors();
			}
		}
	}

	if (EMPTY($teamspace_id)) {
		$type="workspace";
		$id=$GLOBALS['ui']->WorkspaceID();
	}
	else {
		$type="teamspace";
		$id=$ui->TeamspaceID();
	}

	$c.=UserList($type,$id);
	unset ($type);
	unset ($id);

	return $c;
}
?>