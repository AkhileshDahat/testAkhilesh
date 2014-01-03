<?php
/* ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function GetColumnValue($col,$tb,$whr,$val,$extra="") {

	$db=$GLOBALS['db'];

	/* FOR NUMERIC / NULL VALUES */
	if (is_numeric($val) || is_null($val) || $val=="NULL") {
		$whr="WHERE ".$whr." = ".$val;
	}
	else {
		$whr="WHERE ".$whr." = '".$val."'";
	}

	$sql="SELECT $col
				FROM ".$GLOBALS['database_prefix'].$tb."
				$whr
				$extra
				";
	//echo $sql;
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		while($row = $db->FetchArray($result)) {
			return $row[$col];
		}
	}
}
?>