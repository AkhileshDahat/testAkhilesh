<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";

function AddBooking($booking_id="",$facility_id="",$date="",$description="") {

	$GLOBALS['head']->IncludeFile("jscalendar");

	$form=new CreateForm;
	$form->SetCredentials("index.php?module=facilities&task=bookings&jshow=step2","post","add_facility_booking");
	$form->Header("crystalclear/48x48/apps/access.png","Facility Booking Form");
	$form->Hidden("booking_id",$booking_id);
	$form->ShowDropDown("Choose Facility","facility_id","facility_name","facility_master","facility_id",$facility_id,"","WHERE workspace_id=".$GLOBALS['ui']->WorkspaceID()." AND teamspace_id ".$GLOBALS['teamspace_sql']."","");
	$form->Date("Date","date",$date);
	$form->Textarea("Description","description",5,30,$description);
	$form->Submit("Next...","FormSubmit");
	$form->CloseForm();
	return $form->DrawForm();
}

?>