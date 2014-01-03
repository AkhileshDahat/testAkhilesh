<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";

function AddFacility($facility_id="",$facility_name="",$description="",$logo="",$max_user_bookings_week="") {

	$form=new CreateForm;
	$form->SetCredentials("index.php?module=facilities&task=facilities&subtask=addedit","post","add_facility");
	$form->Header("crystalclear/48x48/apps/access.png","Facilities");
	$form->Hidden("facility_id",$facility_id);

	$form->Input("Facility name","facility_name","","","",$facility_name);
	$form->Textarea("Description","description",5,30,$description);
	$form->Input("Logo","logo","logo","add_facility","logo",$logo,40);
	$form->Input("Max user weekly bookings","max_user_bookings_week","","","",$max_user_bookings_week,2);
	$form->Submit("Save","FormSubmit");
	$form->CloseForm();
	return $form->DrawForm();
}

?>