<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function DateDropdown($name,$year_val="",$month_val="",$day_val="") {
	/* YEAR CHECKING */
	if ((!is_numeric($year_val))) { $this_year = date("Y"); } else { $this_year = $year_val; }
	/*DRAW THE YEAR DROPDOWN*/
	$c="<select name='".$name."' class='inputstyle'>";
	for ($i=$this_year-4;$i<$this_year+5;$i++) {
		if ($this_year == $i) { $selected = "selected"; } else { $selected = ""; }
		$c.="<option ".$selected." value='".$i."'>".$i."</option>";
	}
	$c.="</select>";

	/* MONTH CHECKING */
	if (!is_numeric($month_val)) { $this_month = date("m"); } else { $this_month = $month_val;	}
	/* DRAW THE MONTH DROPDOWN */
	$c="<select name='".$name."' class='inputstyle'>";
	for ($i=1;$i<=12;$i++) {
		if ($this_month == $i) { $selected = "selected"; } else {	$selected = "";	}
		$c.="<option ".$selected." value='".$i."'>".$i."</option>";
	}
	$c.="</select>";

	/* DAY CHECKING */
	if (!is_numeric($day_val)) { $this_day = date("d"); } else { $this_day = $day_val; }
	/*DRAW THE DAY DROPDOWN*/
	$c="<select name='".$name."' class='inputstyle'>";
	for ($i=1;$i<=31;$i++) {
		if ($this_day == $i) { $selected = "selected"; } else {	$selected = "";	}
		$c.="<option ".$selected." value='".$i."'>".$i."</option>";
	}
	$c.="</select>";

	return $c;
}
?>