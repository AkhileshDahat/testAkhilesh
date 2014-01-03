<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function CheckCreateACLTaskExists($role_id,$module_id,$task,$workspace_id="",$access="y",$teamspace_id="NULL") {

	if (EMPTY($role_id)) { return False; }
	if (EMPTY($module_id)) { return False; }
	if (EMPTY($task)) { return False; }
	if (EMPTY($workspace_id)) { $workspace_id = $GLOBALS['workspace_id']; }
	if (EMPTY($teamspace_id)) { $teamspace_id = $GLOBALS['teamspace_id']; $teamspace_sql = $GLOBALS['teamspace_sql']; } else { $teamspace_sql = "= ".$teamspace_id; }
	if (EMPTY($teamspace_id)) { $teamspace_id = "NULL"; $teamspace_sql = "IS NULL"; }
	$db=$GLOBALS['db'];

	$sql="SELECT 'x'
				FROM ".$GLOBALS['database_prefix']."core_space_task_acl wta
				WHERE wta.role_id = ".$role_id."
				AND wta.module_id = '".$module_id."'
				AND wta.workspace_id = ".$workspace_id."
				AND wta.teamspace_id ".$teamspace_sql."
				AND wta.task = '".$task."'
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		return True;
	}
	else {
		$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_space_task_acl
					(role_id,module_id,task,access,workspace_id,teamspace_id)
					VALUES (
					".$role_id.",
					'".$module_id."',
					'".$task."',
					'".$access."',
					".$workspace_id.",
					".$teamspace_id."
					)";
		//echo $sql."<br>";
		$success=$db->Query($sql);
		if ($db->AffectedRows($success) > 0) {
			return True;
		}
		else {
			return False;
		}

	}
}
?>