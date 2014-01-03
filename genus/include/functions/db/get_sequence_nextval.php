<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function GetSequenceNextval($sequence) {

	$db=$GLOBALS['db'];
	$sql="SELECT nextval('".$GLOBALS['database_prefix'].$sequence."') as nextval";
	//echo $sql;
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		while($row = $db->FetchArray($result)) {
			return $row['nextval'];
		}
	}
	else {
		return False;
	}
}
?>