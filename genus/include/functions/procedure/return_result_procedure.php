<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function ReturnResultProcedure($proc,$col) {
	$db=$GLOBALS['db'];
	$app_db=$GLOBALS['app_db'];

	$sql="CALL $proc";
	//echo $sql;
	$result = $db->Query($sql);

	$sql="SELECT @a";
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		while($row = $db->FetchArray($result)) {
			return $row[$col];
		}
	}
}
?>