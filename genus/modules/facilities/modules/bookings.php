<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."classes/design/tab_boxes.php";
require_once $GLOBALS['dr']."modules/facilities/classes/facility_master.php";

function LoadTask() {

	/* INCLUDE THE FILES AND PROCESS THEM INTO TABS */
	$tab_array=array("step1","step2","confirm");
	$tb=new TabBoxes();
	$c=$tb->DrawBoxes($tab_array,$GLOBALS['dr']."modules/facilities/modules/bookings/");
	if (ISSET($_GET['jshow'])) {
		$c.=$tb->BlockShow(EscapeData($_GET['jshow']));
	}
	return $c;
}
?>