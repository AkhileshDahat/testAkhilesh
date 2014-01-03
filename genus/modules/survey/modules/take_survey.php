<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/survey/functions/forms/take_survey.php";
//require_once $GLOBALS['dr']."modules/survey/classes/visitor_id.php";

function LoadTask() {

	$ui=$GLOBALS['ui'];

	$c="";

	/* FORM PROCESSING */
	if (ISSET($_POST['FormSubmit']) && !EMPTY($_POST['password'])) {
		session_register('survey_password');
		$_SESSION['survey_password']=$_POST['password'];
		$si=new SurveyID;
		$survey_id=$si->GetSurveyIdFromPass($_SESSION['survey_password']);
		session_register('survey_id');
		$_SESSION['survey_id']=$survey_id;
	}
	if (ISSET($_POST['FormSubmit1']) && ISSET($_SESSION['survey_id'])) {
		//echo "Saving data now";
		$si=new SurveyID;
		$si->SetParameters($_SESSION['survey_id']);
		$si->GetSurveyFormResults();
	}
	if (ISSET($_GET['reset'])) {
		unset($_SESSION['survey_id']);
		unset($_SESSION['survey_password']);
	}
	if (ISSET($_GET['confirm'])) {
		unset($_SESSION['survey_id']);
		unset($_SESSION['survey_password']);
		$c.=CurveBox("Thank you for taking time to complete this survey<br>");
	}

	/*
		DESIGN THE FORM
	*/
	$c.=TakeSurvey();

	/* SOME FOOTER INFORMATION */
	if (!ISSET($si)) {
		$si=new SurveyID;
	}
	if (ISSET($_SESSION['survey_password'])) {
		$c.="<table class='plain_border'>\n";
		$c.="<tr>\n";
			$c.="<td>Survey:</td>\n";
			$c.="<td></td>\n";
			$c.="<td><a href='index.php?module=survey&task=take_survey&confirm=y'>Confirm and Complete</a></td>\n";
			$c.="<td><a href='index.php?module=survey&task=take_survey&reset=y'>Reset</a></td>\n";
		$c.="</tr>\n";
		$c.="</table>\n";
	}

	return $c;
}
?>