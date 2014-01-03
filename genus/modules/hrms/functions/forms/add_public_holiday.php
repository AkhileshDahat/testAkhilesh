<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."classes/form/create_form.php";

require_once $GLOBALS['dr']."modules/hrms/classes/public_holiday.php";

function AddPublicHoliday($date_pub_hol="") {
	$db=$GLOBALS['db'];
	$database_prefix=$GLOBALS['database_prefix'];
	$wb=$GLOBALS['wb'];

	$c="";

	$show_form=True;

	/* INCLUDE THE JCALENDAR */
	$GLOBALS['head']->IncludeFile("jscalendar");

	if (ISSET($_POST['user_id']) && ISSET($_POST['date_pub_hol'])) {

		$ur=new PublicHoliday;
		$result=$ur->Add($_POST['date_pub_hol']);
		if ($result) {
			$c.=ErrorPageTop("success","Success");
		}
		else {
			$c.=ErrorPageTop("fail",$ur->ShowErrors());
		}
	}

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=hrms&task=public_holidays","post","add_public_holiday","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Public Holidays");

	$form->Date("Date","date_pub_hol",$date_pub_hol);

	if ($show_form) {
		$c.=$form->DrawForm();
	}
	return $c;
}
?>