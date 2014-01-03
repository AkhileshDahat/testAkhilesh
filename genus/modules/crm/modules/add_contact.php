<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/crm/functions/forms/add_contact.php";
require_once $GLOBALS['dr']."modules/crm/classes/contact_id.php";

function LoadTask() {

	$ui=$GLOBALS['ui'];

	$c="";

	/* FORM PROCESSING */
	if (ISSET($_POST['contact_name'])) {

		$ai=new AccountID;
		$result_add=$ai->Add($_POST['contact_name'],$_POST['industry_id'],$_POST['user_id_assigned'],$_POST['phone_number'],
											$_POST['fax_number'],$_POST['email_address'],$_POST['account_type_id'],
											$_POST['billing_address'],$_POST['billing_city'],$_POST['billing_state'],$_POST['billing_postal_code'],$_POST['billing_country_id'],
											$_POST['shipping_address'],$_POST['shipping_city'],$_POST['shipping_state'],$_POST['shipping_postal_code'],$_POST['shipping_country_id'],
											$_POST['other_info']);
		if (!$result_add) {
			$c.="Failed";
			$c.=$ai->ShowErrors();
		}
		else {
			$c.="Success";
		}
	}

	/* FORM VALIDATION */
	/*
	$fv=new FieldValidation;
	$fv->OpenTag();
	$fv->ValidateFields("contact_name");
	$fv->CloseTag();
	*/

	/*
		DESIGN THE FORM
	*/
	$c.=AddContact();

	return $c;
}
?>