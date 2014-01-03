<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/facilities/functions/forms/add_booking.php";

function Step1() {
	return CurveBox(AddBooking());
}
?>