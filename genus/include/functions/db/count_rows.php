<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function CountRows($tb, $rw, $fld, $extra="") {

	$db=$GLOBALS['db'];
	$sql="SELECT count($fld) as count
				FROM ".$GLOBALS['database_prefix']."$tb
				WHERE $rw = '$fld'
				$extra";

	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		while($row = $db->FetchArray($result)) {
			return $row['count'];
	 	}
	}
	else {
		return 0;
	}
}
?>