<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/scheduling/classes/scheduling_lecturer_reserved_slots.php";
require_once $GLOBALS['dr']."modules/scheduling/functions/browse/browse_reserve_timetable.php";

function LoadTask() {

	$c="";

	/*
	**************************************************************************
		PERFORM THE ACTIONS HERE BEFORE WE START DISPLAYING STUFF
	***************************************************************************
	*/
	if (ISSET($_GET['save'])) {
		/* CREATE THE OBJECT */
		$obj_slrs=new SchedulingLecturerReservedSlots;
		/* ADD - NO ID*/
		if (!ISSET($_POST['category_id']) || EMPTY($_POST['category_id'])) {
			$result_add=$obj_slrs->Add($_GET['time_start'],$_GET['time_end'],$_GET['day_of_week'],"own");
		}

		if (!$result_add) {
			$c.="Failed";
			$c.=$obj_slrs->ShowErrors();
		}
		else {
			$c.="Success";
		}
	}

	/* DELETE */
	if (ISSET($_GET['reset'])) {

		$obj_slrs=new SchedulingLecturerReservedSlots;
		$result_del=$obj_slrs->Reset();
		if (!$result_del) {
			$c.="Failed";
			$c.=$obj_slrs->ShowErrors();
		}
		else {
			$c.="Success";
		}
	}

	$c.=BrowseReserveTimetable();

	return $c;
}
?>