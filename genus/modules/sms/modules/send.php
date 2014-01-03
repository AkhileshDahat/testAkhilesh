<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/sms/functions/forms/send.php";
require_once $GLOBALS['dr']."modules/sms/classes/sms_id.php";

function LoadTask() {

	$ui=$GLOBALS['ui'];
	$show_form=True;
	$c="";

	/* FORM PROCESSING */
	if (ISSET($_POST['FormSubmit'])) {
		$show_form=False;
		$obj_si=new SMSID;
		$obj_si->GetFormPostedValues();
		$result=$obj_si->Send();
		if ($result) { $c.="ok, sent to ".$obj_si->GetInfo("count")." users"; } else { $c.="failed"; $c.=$obj_si->ShowErrors(); }
	}
	else {
	}

	/*
		DESIGN THE FORM
	*/
	if ($show_form) {
		$c.=Send();
	}

	return $c;
}
?>