<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/announcements/functions/forms/add.php";
require_once $GLOBALS['dr']."modules/announcements/classes/announcement_id.php";

function LoadTask() {

	$ui=$GLOBALS['ui'];
	$show_form=True;
	$c="";

	/* FORM PROCESSING */
	if (ISSET($_POST['FormSubmit'])) {
		$show_form=False;
		$obj_ai=new AnnouncementID;
		$obj_ai->GetFormPostedValues();
		$result=$obj_ai->Add();
		if ($result) { $c.="ok"; } else { $c.="failed"; $c.=$obj_si->ShowErrors(); }
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