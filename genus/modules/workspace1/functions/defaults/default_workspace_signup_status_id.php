<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function DefaultWorkspaceSignupStatusID() {
	$db=$GLOBALS['db'];

	$sql="SELECT status_id
				FROM ".$GLOBALS['database_prefix']."core_space_status_master
				WHERE default_signup = 'y'
				";
	//echo $sql;
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		while($row = $db->FetchArray($result)) {
			return $row['status_id'];
		}
	}
	else {
		return False;
	}
}
?>