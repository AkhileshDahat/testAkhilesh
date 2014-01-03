<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."modules/facilities/classes/booking_master.php";

function Confirm() {
	$c="";
	/* SAVE THE RECORD */
	if (ISSET($_POST['BookingConfirm'])) {
		$bm=new BookingMaster($_POST['booking_id'],$_POST['facility_id'],$_POST['date']." ".$_POST['time_from'],$_POST['date']." ".$_POST['time_to'],$_SESSION['user_id'],$_POST['description']);
		$result=$bm->AddBooking();
		if ($result) {
			$c.=Alert(97);
		}
		else {
			$c.=Alert(96,$bm->ShowErrors());
		}
		$show_button=False;
	}
	else {
		$show_button=True;
	}
	if (ISSET($_POST['booking_id']) && ISSET($_POST['facility_id']) && ISSET($_POST['date']) &&
			ISSET($_POST['description']) && ISSET($_POST['time_from']) && ISSET($_POST['time_to'])) {
		/* SHOW A CONFIRMATION FORM */
		$form=new CreateForm;
		$form->SetCredentials("index.php?module=facilities&task=bookings&jshow=confirm","post","add_booking_confirm");
		$form->Header("crystalclear/48x48/apps/access.png","Facility Booking Form - Confirmation");
		$form->Hidden("booking_id",$_POST['booking_id']);
		$form->Hidden("facility_id",$_POST['facility_id']);
		$form->Hidden("date",$_POST['date']);
		$form->Hidden("description",$_POST['description']);
		$form->Hidden("time_from",$_POST['time_from']);
		$form->Hidden("time_to",$_POST['time_to']);

		$form->DescriptionCell("Facility",$_POST['facility_id']);
		$form->DescriptionCell("Date",$_POST['date']);
		$form->DescriptionCell("Time",$_POST['time_from']." to ".$_POST['time_to']);
		$form->DescriptionCell("Description",$_POST['description']);

		if ($show_button) {
			$form->Submit(" Save ","BookingConfirm");
		}
		$form->CloseForm();

		$c.=CurveBox($form->DrawForm());
	}
	else {
		$c.="Please go to step 2 first";
	}

	return $c;
}
?>
