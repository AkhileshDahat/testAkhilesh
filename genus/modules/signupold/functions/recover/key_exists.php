<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function KeyExists($secret_string) {
	$db=$GLOBALS['db'];

	$sql="SELECT user_id
				FROM ".$GLOBALS['database_prefix']."core_user_password_recovery
				WHERE secret_string = '".EscapeData($secret_string)."'
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		while($row = $db->FetchArray($result)) {
			return $row['user_id'];
		}
	}
	else {
		return False;
	}
}
?>