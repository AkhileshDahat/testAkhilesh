<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function YearMonthRangeDropdown($select_name,$p_date_from,$p_date_to,$selected="") {

	/* EXPECT THE SELECTED AS YYYY-MM */
	if (!EMPTY($selected)) {
		$tempdate = split('-', $selected);
		$year_start=$tempdate[0];
		$month_start=$tempdate[1];
	}
	else {
		$year_start=NULL;
		$month_start=NULL;
	}

	$c="";

	$c.="<select name=".$select_name.">\n";

	$v_from_year=date("Y",$p_date_from);
	$v_to_year=date("Y",$p_date_to);

	for ($i=$v_from_year;$i<=$v_to_year;$i++) {

		$v_month_from=date("n",$p_date_from);
		//echo $v_month_from."<br>";
		$v_month_to=date("n",$p_date_to);
		//echo $i;
		for ($j=$v_month_from;$j<=$v_month_to;$j++) {
			//echo $j."<br>";
			if ($i==$year_start && $j==$month_start) { $selected="selected"; } else { $selected=""; }
			if ($j<10) { $j_show="0".$j; } else { $j_show=$j; }
			$c.="<option value='".$i."-".$j_show."' ".$selected.">".$i."-".$j_show."</option>\n";

		}
	}

	$c.="</select>\n";

	return $c;
}
?>
