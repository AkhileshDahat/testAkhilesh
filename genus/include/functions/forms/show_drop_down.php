<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function ShowDropDown($f1, $f2, $tb, $sn, $sl,$sql="",$sql_extra="",$multiple="",$class="input") {
	$db=$GLOBALS['db'];
	$database_prefix=$GLOBALS['database_prefix'];
 	if (EMPTY($sql)) {
	 	$sql="SELECT DISTINCT $f1 as $f1, $f2 as $f2
	 	      FROM ".$GLOBALS['database_prefix']."$tb
	 	      $sql_extra
	 	      ORDER BY $f2";
	}

	//echo $sql;
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		$drop_down = "<select name='".$sn."' class='$class' ".$multiple.">";
		$drop_down .= "<option value=''>--==Select One==--</option>";
		while($row = $db->FetchArray($result)) {
			if ($sl == $row[$f1]) {
				$sel="selected";
			}
			else {
				$sel="";
			}
			$drop_down .= "<option value='".@$row[$f1]."' ".$sel.">".@$row[$f2]."</option>\n";
		}
		$drop_down .= "</select>";
		return $drop_down;
	}
	else {
		return "No results found!";
	}
	mysql_free_result($result);
}
?>