<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function WorkspaceModuleRoleExists($workspace_id,$module_id,$role_id) {
	$db=$GLOBALS['db'];

	$sql="SELECT 'x'
				FROM ".$GLOBALS['database_prefix']."core_space_module_acl
				WHERE workspace_id = '".$workspace_id."'
				AND module_id = '".$module_id."'
				AND role_id = '".$role_id."'
				";
	//echo $sql;
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		return True;
	}
	else {
		return False;
	}
}
?>