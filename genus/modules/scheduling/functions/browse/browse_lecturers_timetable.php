<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."modules/scheduling/classes/display_timetable.php");
require_once $GLOBALS['dr']."modules/scheduling/classes/scheduling_lecturer_timetable.php";

global $obj_slt;

function BrowseLecturersTimetable() {

	$obj_st=new DisplayTimetable;
	$obj_st->SetVariable("arr_days",array("mon","tue","wed","thu","fri","sat","sun"));
	$obj_st->SetVariable("start_time",450);
	$obj_st->SetVariable("end_time",1050);
	$obj_st->SetVariable("interval",60);
	$obj_st->SetVariable("callback_function","DrawButton");
	//$obj_st->SetVars();

	$obj_st->InitTableArray();

	$c=$obj_st->DrawInitTableArray();

	return $c;

}

/* CREATE THE OBJECT FOR CHECKING BOOKED SLOTS */
$obj_slt=new SchedulingLecturerTimetable;

/* CALL BACK FUNCTION */
function DrawButton($p_day,$p_start_time,$p_end_time) {
	//echo $p_start_time."<br>";
	global $obj_slt;

	$user_id="";
	if (ISSET($_GET['user_id'])) {
		$user_id=EscapeData($_GET['user_id']);
	}

	$booked=$obj_slt->SlotIsReserved($user_id,$p_start_time,$p_end_time,$p_day,"own");
	if (!$booked) {
		return " ";
	}
	else {
		$subject=$obj_slt->GetInfo("slot_subject_name");
		$location=$obj_slt->GetInfo("slot_resource_name");
		return $subject."1<br>".$location;
	}
}

?>