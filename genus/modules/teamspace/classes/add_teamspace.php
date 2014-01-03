<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function AddTeamspace($workspace_id,$teamspace_name) {
	$db=$GLOBALS['db'];
	$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_teamspace_master
				(workspace_id,name)
				VALUES (
				'".$workspace_id."',
				'".$teamspace_name."'
				)";
	$result=$db->query($sql);
	if ($result) {
		return True;
	}
	else {
		return False;
	}
}
?>