<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* THIS IS THE AUTODROPDOWN WHICH WHEN SELECTED REDIRECTS THE USER TO THE URL SPECIFIED */
function AutoRedirectDropdown($f1,$f2,$tb,$sl,$sel,$qst,$sql="",$extra_sql="") {
	$db=$GLOBALS['db'];

	$d="<script language='JavaScript'>\n";
	$d.="<!-- Hide from old browsers\n";
	$d.="function Jumpto(form) {\n";
		$d.="var myindex=form.jump_to.selectedIndex\n";
	  $d.="if (form.jump_to.options[myindex].value != '0') {\n";
	  	$d.="location=form.jump_to.options[myindex].value;}\n";
		$d.="}\n";
	$d.="//-->\n";
	$d.="</script>\n";

	if (EMPTY($sql)) {

	 	$sql="SELECT LTRIM($f1) as $f1, LTRIM($f2) as $f2
	 	      FROM ".$GLOBALS['database_prefix']."$tb
	 	      $extra_sql
	 	      ORDER BY $f2";
	}

	//$d.=$sql;
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		$drop_down="<table cellpadding=0 cellspacing=0 border=0><form><tr><td>\n";
		$drop_down.="<select name='jump_to' class='new_inputstyle' onChange='Jumpto(this.form)'>\n";
		$drop_down.="<option value='$qst'>-Filter-</option>\n";
		while($row=$db->FetchArray($result)) {
			if ($sel == $row[$f1]) { $sel="selected"; } else {	$sel=""; }
			$drop_down = $drop_down."<option value='".$qst.$row[$f1]."' ".$sel.">".$row[$f2]."</option>\n";
		}
		$drop_down = $drop_down. "</select>\n";
		$drop_down = $drop_down. "</td></tr></form></table>\n";
		$d.=$drop_down;
	}
	else {
		$d.="Not setup.";
	}
	return $d;
}
?>