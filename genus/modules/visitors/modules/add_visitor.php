<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/visitors/functions/forms/add_visitor.php";
require_once $GLOBALS['dr']."modules/visitors/classes/visitor_id.php";

function LoadTask() {

	$ui=$GLOBALS['ui'];
	$fail=False;
	$show_form=True;
	$c="";

	/* FORM PROCESSING */
	if (ISSET($_POST['FormSubmit'])) {
		//echo "adding";
		$vi=new VisitorID;
		$vi->GetFormPostedValues();
		$result_add=$vi->Add();
		if (!$result_add) {
			$c.="Failed";
			$c.=$vi->ShowErrors();
			$fail=True;
		}
		else {
			$c.="Success";
			$show_form=False;
		}
		$visitor_category_id=CheckPostData("visitor_category_id");
		$remarks=$_POST['remarks'];
		$date_expected=$_POST['date_expected'];
		$visitor_full_name=$_POST['visitor_full_name'];
		$visitor_identification_number=$_POST['visitor_identification_number'];
		$visitor_contact_number=$_POST['visitor_contact_number'];
		$total_guests=$_POST['total_guests'];
		$vehicle_registration_number=$_POST['vehicle_registration_number'];
	}
	else {
		$visitor_category_id="";
		$remarks="";
		$date_expected="";
		$visitor_full_name="";
		$visitor_identification_number="";
		$visitor_contact_number="";
		$total_guests="";
		$vehicle_registration_number="";
	}

	/*
		DESIGN THE FORM
	*/
	if ($show_form) {
		$c.=AddVisitor($visitor_category_id,$remarks,$date_expected,$visitor_full_name,$visitor_identification_number,$visitor_contact_number,
									$total_guests,$vehicle_registration_number);
	}

	return $c;
}
?>