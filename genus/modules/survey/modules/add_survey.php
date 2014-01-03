<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/survey/functions/forms/add_survey.php";
require_once $GLOBALS['dr']."modules/survey/classes/survey_id.php";

function LoadTask() {

	$ui=$GLOBALS['ui'];

	$c="";

	/* FORM PROCESSING */
	if (ISSET($_POST['FormSubmit'])) {
		//echo "adding";
		$si=new surveyID;
		$si->GetFormPostedValues();
		$result_add=$si->Add();
		if (!$result_add) {
			$c.="Failed";
			$c.=$si->ShowErrors();
		}
		else {
			$c.="Success";
		}
	}

	/*
		DESIGN THE FORM
	*/
	$c.=AddSurvey();

	return $c;
}
?>