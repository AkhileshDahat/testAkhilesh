<?php
header("Pragma: no-cache");
define( '_VALID_MVH', 1 );

require_once "../../../config.php";
require_once "../../../db_config.php";
require_once "../../../common_config.php";

require_once "../config.php";

if (ISSET($_GET['question_id']) &&  ISSET($_GET['answer'])) {
	require_once "../classes/survey_id.php";
	$si=new SurveyID;
	$si->SetParameters($_SESSION['survey_id']);
	$success_result=$si->SaveAnswer($_GET['question_id'],$_GET['answer']);

	if ($success_result) {
		echo "Saved";
	}
	else {
		echo "Not saved";
		echo $si->ShowErrors();
	}
}
?>