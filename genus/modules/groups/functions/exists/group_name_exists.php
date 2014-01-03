<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function GroupNameExists($user_id,$workspace_id,$teamspace_id,$group_name) {
	$db=$GLOBALS['db'];
	if (is_null($teamspace_id)) { $teamspace_id="NULL"; }
	$sql="SELECT 'x'
				FROM ".$GLOBALS['database_prefix']."group_master
				WHERE user_id = ".$user_id."
				AND workspace_id = ".$workspace_id."
				AND teamspace_id = ".$teamspace_id."
				AND group_name = '".$group_name."'
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