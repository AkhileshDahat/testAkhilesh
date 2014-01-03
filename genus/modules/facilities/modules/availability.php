<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/facilities/functions/browse/facilities_availability.php";

function LoadTask() {

	/* QUICK BOOKING */
	if (ISSET($_GET['quick_booking'])) {
		require_once $GLOBALS['dr']."modules/facilities/classes/booking_master.php";
		$bm=new BookingMaster("",$_GET['facility_id'],$_GET['date']." ".$_GET['time_from'].":00",$_GET['date']." ".$_GET['time_to'].":00",$_SESSION['user_id'],"One-Touch Booking");
		$result=$bm->AddBooking();
		//if ($result){ MobileAlert(21,"Success"); } else { MobileAlert(22,"Failed"); }
	}

	if (ISSET($_POST['facility_id'])) { $facility_id=$_POST['facility_id']; } else { $facility_id=""; } /* FOR QUERY */
	if (ISSET($_GET['facility_id'])) { $facility_id=$_GET['facility_id']; } /* FOR BOOKING */
	if (ISSET($_POST['date'])) { $date=$_POST['date']; } else { $date=""; } /* FOR QUERY */
	if (ISSET($_GET['date'])) { $date=$_GET['date']; } /* FOR BOOKING */

	if (!defined( '_VALID_MVH_MOBILE_' )) {
		return CurveBox(FacilitiesAvailability($facility_id,$date));
	}
	else {
		return FacilitiesAvailability($facility_id,$date);
	}
}
?>