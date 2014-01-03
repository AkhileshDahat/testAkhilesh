<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/scheduling/classes/scheduling.php";

function LoadTask() {

	$c="";

	/*
	**************************************************************************
		PERFORM THE ACTIONS HERE BEFORE WE START DISPLAYING STUFF
	***************************************************************************
	*/

	$obj_sch=new Scheduling();

	$c.=$obj_sch->RunSchedule();

	if (!$c) {
		$c.=$obj_sch->ShowErrors();
	}

	return $c;
}
?>