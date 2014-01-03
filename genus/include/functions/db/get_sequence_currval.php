<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function GetSequenceCurrval($sequence) {

	$db=$GLOBALS['db'];
	$sql="SELECT currval('".$GLOBALS['database_prefix'].$sequence."') AS currval";
	//echo $sql;
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		while($row = $db->FetchArray($result)) {
			return $row['currval'];
		}
	}
	else {
		return False;
	}
}
?>