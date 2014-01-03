<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function DefaultWorkspaceRoleID($workspace_id) {
	$db=$GLOBALS['db'];

	$sql="SELECT role_id
				FROM ".$GLOBALS['database_prefix']."core_workspace_role_master
				WHERE workspace_id = '".$workspace_id."'
				AND default_role = 'y'
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		while($row = $db->FetchArray($result)) {
			return $row['role_id'];
		}
	}
	else {
		return False;
	}
}
?>