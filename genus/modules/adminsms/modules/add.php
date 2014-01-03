<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/adminsms/functions/forms/add.php";
require_once $GLOBALS['dr']."modules/adminsms/classes/transaction_id.php";

function LoadTask() {

	$ui=$GLOBALS['ui'];
	$show_form=True;
	$c="";

	/* FORM PROCESSING */
	if (ISSET($_POST['FormSubmit'])) {
		$show_form=False;
		$obj_ti=new TransactionID;
		$obj_ti->GetFormPostedValues();
		$result=$obj_ti->Add();
		if ($result) { $c.="ok"; } else { $c.="failed"; $c.=$obj_ti->ShowErrors(); }
	}
	else {
	}

	/*
		DESIGN THE FORM
	*/
	if ($show_form) {
		$c.=Add();
	}

	return $c;
}
?>