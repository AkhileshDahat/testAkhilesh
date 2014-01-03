<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/survey/classes/survey_id.php";
require_once $GLOBALS['dr']."modules/survey/functions/browse/browse_survey_questions.php";
require_once $GLOBALS['dr']."modules/survey/functions/forms/form_survey_questions.php";

function LoadTask() {
	$c="";	
	
	if (ISSET($_GET['survey_id'])) {
		$survey_id=$_GET['survey_id'];
	}
	else {
		return "Error";
	}
	
	/* FORM PROCESSING */
	if (ISSET($_POST['FormSubmit'])) {
		echo "adding";
		echo $_POST['survey_id'];
		$si=new surveyID;
		$si->GetFormPostedValuesQuestions();
		$si->SetVariable("survey_id",$survey_id);
		$result_add=$si->AddQuestion();
		if (!$result_add) {
			$c.="Failed";
			$c.=$si->ShowErrors();
		}
		else {
			$c.="Success";
		}
	}
	if (ISSET($_GET['subtask'])) {
		if ($_GET['subtask']=="delete") {
			$si=new surveyID;
			$si->SetVariable("survey_id",$survey_id);
			$si->SetVariable("question_id",$_GET['question_id']);
			$result_del=$si->DeleteQuestion();
			if (!$result_del) {
				$c.="Failed";
				$c.=$si->ShowErrors();
			}
			else {
				$c.="Success";
			}
		}
	}
	
	/* THE FORM FOR ADDING NEW QUESTIONS */
	$c.=FormSurveyQuestions($survey_id);
	
	/* INCLUDE THE BROWSE FUNCTION */
	$c.=BrowseSurveyQuestions($survey_id);

	return $c;
}
?>