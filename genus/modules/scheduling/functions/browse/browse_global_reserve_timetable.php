<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."modules/scheduling/classes/display_timetable.php");
require_once $GLOBALS['dr']."modules/scheduling/classes/scheduling_global_reserved_slots.php";

global $obj_sgrs;

function BrowseGlobalReserveTimetable() {

	$obj_st=new DisplayTimetable;
	$obj_st->SetVariable("arr_days",array("mon","tue","wed","thu","fri","sat","sun"));
	$obj_st->SetVariable("start_time",450);
	$obj_st->SetVariable("end_time",1050);
	$obj_st->SetVariable("interval",60);
	$obj_st->SetVariable("callback_function","DrawButton");
	//$obj_st->SetVars();

	$obj_st->InitTableArray();

	$c=$obj_st->DrawInitTableArray();

	$c.="<br />\n";
	$c.="<input type='button' name='reser_button' value='Reset All' onClick=\"document.location.href='index.php?module=scheduling&task=global_reserve_slots&reset=yes'\">";

	return $c;

}

/* CREATE THE OBJECT FOR CHECKING BOOKED SLOTS */
$obj_sgrs=new SchedulingGlobalReservedSlots;

/* CALL BACK FUNCTION */
function DrawButton($p_day,$p_start_time,$p_end_time) {
	global $obj_sgrs;
	if ($obj_sgrs->SlotIsReserved($p_start_time,$p_end_time,$p_day)) {
		return "Booked";
	}
	else {
		return "<input type='button' name='block_button' value='Block' onClick=\"document.location.href='index.php?module=scheduling&task=global_reserve_slots&save=yes&time_start=$p_start_time&time_end=$p_end_time&day_of_week=$p_day'\">\n";
	}
}

?>