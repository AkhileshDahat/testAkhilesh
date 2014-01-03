<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/facilities/functions/forms/add_booking_times.php";

function Step2() {
	$c="";
	if (ISSET($_POST['FormSubmit'])) {
		$c.=CurveBox(AddBookingTimes($_POST['booking_id'],$_POST['facility_id'],$_POST['date'],$_POST['description']));
	}
	else {
		$c.="Please complete step 1 first!<br>";
	}
	return $c;
}
?>