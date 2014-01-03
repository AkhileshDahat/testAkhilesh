<?php
function TransferSelect($form_val, $disp_val, $sql, $sql1, $hd_from, $hd_to, $size=10, $name="select") {

	$db=$GLOBALS['db'];

	$s="<table width='100%'>\n";
		$s.="<tr>\n";
			$s.="<td width='45%' class='font12b'>".$hd_from."</td>\n";
			$s.="<td width='45%' class='font12b'>".$hd_to."</td>\n";
		$s.="</tr>\n";
		$s.="<tr>\n";
			$s.="<td>\n";
			$s.="<select name='".$name."1[]' multiple size='".$size."' class='inputstyle'>\n";

			/* WE PASS THE SQL QUERY INTO THE FUNCTION AND DISPLAY THE FIRST DROPDOWN FIELD */
		  $result = $db->Query($sql);
			if ($db->NumRows($result) > 0) {
				while ($row = $db->FetchArray($result))	{
					$s.="<option value='".mysql_escape_string($row[$form_val])."'>".$row[$disp_val]."</option>\n";
				}
			}

			$s.="</select>\n";
			$s.="</td>\n";
			$s.="<td>\n";
			$s.="<select name='".$name."2[]' multiple size='".$size."' class='inputstyle'>\n";

			/* WE PASS THE SQL QUERY INTO THE FUNCTION AND DISPLAY THE SECOND DROPDOWN FIELD */
		  $result = $db->query($sql1);
			if ($db->NumRows($result) > 0) {
				while ($row = $db->FetchArray($result))	{
					$s.="<option value='".mysql_escape_string($row[$form_val])."'>".$row[$disp_val]."</option>\n";
				}
			}
			$s.="</select>\n";
			$s.="</td>\n";
		$s.="</tr>\n";
	$s.="</table>\n";

	return $s;
}
?>