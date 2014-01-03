<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."modules/hrms/classes/users_pending_approval.php");
require_once($GLOBALS['dr']."modules/hrms/classes/user_id.php");

function LoadTask() {

	$c="";

	$teamspace_id=$GLOBALS['ui']->TeamspaceID();

	if (ISSET($_GET['subtask'])) {
		if ($_GET['subtask']=="approve" && IS_NUMERIC($_GET['user_id'])) {
			$c.="Approving...<br>";
			$obj_id=new UserID;
			$result=$obj_id->SetParameters($_GET['user_id']);
			$result_approve=$obj_id->Approve();
			if ($result && $result_approve) {
				$c.="Success<br>";
			}
			else {
				$c.="Failed<br>";
				$c.=$obj_id->ShowErrors();
			}
		}
	}
	$obj_upa=new UsersPendingApproval;

	$c.=$obj_upa->ShowUsers();

	return $c;
}
?>