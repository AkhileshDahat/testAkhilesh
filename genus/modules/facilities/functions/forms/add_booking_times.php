<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";

function AddBookingTimes($booking_id="",$facility_id="",$date="",$description="",$time_from="",$time_to="") {

	$form=new CreateForm;
	$form->SetCredentials("index.php?module=facilities&task=bookings&jshow=confirm","post","add_facility_booking");
	$form->Header("crystalclear/48x48/apps/access.png","Facility Booking Form");
	$form->Hidden("booking_id",$booking_id);
	$form->Hidden("facility_id",$facility_id);
	$form->Hidden("date",$date);
	$form->Hidden("description",$description);
	$form->TimeFromTo("Time","time_from","time_to",$time_from,$time_to,"","");
	$form->Submit("Next...","FormSubmit");
	$form->CloseForm();
	return $form->DrawForm();
}

?>