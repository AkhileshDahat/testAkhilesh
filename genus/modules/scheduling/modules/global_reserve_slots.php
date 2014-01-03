<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/scheduling/classes/scheduling_global_reserved_slots.php";
require_once $GLOBALS['dr']."modules/scheduling/functions/browse/browse_global_reserve_timetable.php";

function LoadTask() {

	$c="";

	/*
	**************************************************************************
		PERFORM THE ACTIONS HERE BEFORE WE START DISPLAYING STUFF
	***************************************************************************
	*/
	if (ISSET($_GET['save'])) {
		/* CREATE THE OBJECT */
		$obj_sgrs=new SchedulingGlobalReservedSlots;
		$result_add=$obj_sgrs->Add($_GET['time_start'],$_GET['time_end'],$_GET['day_of_week']);

		if (!$result_add) {
			$c.="Failed";
			$c.=$obj_sgrs->ShowErrors();
		}
		else {
			$c.="Success";
		}
	}

	/* DELETE */
	if (ISSET($_GET['reset'])) {

		$obj_sgrs=new SchedulingGlobalReservedSlots;
		$result_del=$obj_sgrs->Reset();
		if (!$result_del) {
			$c.="Failed";
			$c.=$obj_sgrs->ShowErrors();
		}
		else {
			$c.="Success";
		}
	}

	$c.=BrowseGlobalReserveTimetable();

	return $c;
}
?>