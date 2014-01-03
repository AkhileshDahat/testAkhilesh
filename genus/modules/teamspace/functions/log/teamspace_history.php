<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function TeamspaceHistory($teamspace_id,$description,$user_id) {
	$db=$GLOBALS['db'];

	if (!IS_NUMERIC($teamspace_id)) { return False; }
	if (EMPTY($description)) { return False; }
	if (!IS_NUMERIC($user_id)) { return False; }

	$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_history (teamspace_id,description,user_id,log_date)
				VALUES (
				".$teamspace_id.",
				'".EscapeData($description)."',
				".$user_id.",
				now()
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