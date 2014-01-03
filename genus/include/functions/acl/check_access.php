<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function CheckAccess($role_id,$module_id,$task) {

	$db=$GLOBALS['db'];

	$sql="SELECT 'x'
				FROM ".$GLOBALS['database_prefix']."core_space_task_acl cta
				WHERE cta.role_id = ".$role_id."
				AND cta.module_id = '".$module_id."'
				AND cta.task = '".$task."'
				AND cta.access = 'y'
				AND cta.workspace_id = ".$GLOBALS['workspace_id']."
				AND cta.teamspace_id ".$GLOBALS['teamspace_sql']."
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		return True;
	}
	else {
		return False;
	}
}
?>