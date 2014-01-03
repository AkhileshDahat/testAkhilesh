<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/date_time/date_to_seconds.php";
require_once $GLOBALS['dr']."include/functions/date_time/day_of_week.php";

function FriendlyDateFromTo($p_from,$p_to) {

	/* CONVERT THE ISO DATES TO TIMESTAMPS*/
	$v_from=DateToSeconds($p_from);
	$v_to=DateToSeconds($p_to);

	/* SPLIT THE DATES ABOVER INTO AN ARRAY */
	$v_arr_from = split('[- :]', $p_from);
	$v_arr_to = split('[- :]', $p_to);

	/* CHECK IF IT'S TODAY */
	$v_yesterday=mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"));
	$v_tomorrow=mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));
	if ($v_from>$v_yesterday && $v_to<$v_tomorrow && ($v_from-$v_to) < 86400) {
		return "Today ".$v_arr_from[3].":".$v_arr_from[4]." to ".$v_arr_to[3].":".$v_arr_to[4];
	}
	else {
		return Date("D",$v_from)." ".Date("j",$v_from)." ".Date("M",$v_from)." ".$v_arr_from[3].":".$v_arr_from[4]." to ".Date("D",$v_to)." ".Date("j",$v_from)." ".Date("M",$v_from)." ".$v_arr_to[3].":".$v_arr_to[4];
	}

}
?>