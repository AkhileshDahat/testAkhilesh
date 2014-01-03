<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );
// NOT WORKING - UNTIL I CAN FIGURE OUT HOW TO LIMIT THE NUMBER OF WORKSPACES
function CountRoleWorkspaces($role_id) {
	$db=$GLOBALS['db'];
	$count=0;
	$sql="SELECT count(*) as total
				FROM ".$GLOBALS['database_prefix']."workspace_users wu
				WHERE wu.user_id = '".$user_id."'
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		while($row = $db->FetchArray($result)) {
			$count=$row['total'];
		}
	}
	return $count;
}

?>