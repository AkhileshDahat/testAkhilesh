<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."modules/groups/functions/browse/group_list.php";
require_once $dr."modules/groups/classes/delete_group.php";

if (ISSET($_GET['subtask'])) {
	if ($_GET['subtask']=="delete") {
		$dg=new DeleteGroup;
		$result=$dg->Remove($_SESSION['user_id'],$_GET['group_id']);
		if ($result) {
			echo "Success";
		}
		else {
			echo "Failure";
		}
	}
}

echo CurveBox(GroupList($_SESSION['user_id'],$ui->WorkspaceID(),$ui->TeamspaceID()));
?>