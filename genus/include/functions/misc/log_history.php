<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function LogHistory($description) {
	$db=$GLOBALS['db'];

	if (EMPTY($description)) { return False; }

	$module = "";
	$task = "";
	if (ISSET($_GET['module'])) { $module = $_GET['module']; }
	if (ISSET($_GET['task'])) { $task = $_GET['task']; }

	$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_history (workspace_id,teamspace_id,description,user_id,module,task,log_date)
				VALUES (
				'".$GLOBALS['workspace_id']."',
				'".$GLOBALS['teamspace_id']."',
				'".EscapeData($description)."',
				'".$_SESSION['user_id']."',
				'".EscapeData($module)."',
				'".EscapeData($task)."',
				sysdate()
				)";
	$result=$db->Query($sql);
	if ($db->AffectedRows($result) > 0) {
		return True;
	}
	else {
		return False;
	}
}

?>