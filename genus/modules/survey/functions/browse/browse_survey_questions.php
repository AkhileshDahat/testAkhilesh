<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");
require_once($GLOBALS['dr']."modules/documents/classes/category_id.php");

function BrowseSurveyQuestions($survey_id) {

	$c="";

	$sr=new ShowResults;
	$sr->SetParameters(True);

	$sr->DrawFriendlyColHead(array("","Question","Delete")); /* COLS */
	$sr->Columns(array("question_id","question","del"));

	$sql="SELECT qm.question_id,qm.question,'del' as del
							FROM ".$GLOBALS['database_prefix']."survey_question_master qm
							WHERE qm.survey_id = '".$survey_id."'
							ORDER BY question_id DESC
							";
	//echo $sql;
	$sr->Query($sql);
	for ($i=0;$i<$sr->CountRows();$i++) {
		$question_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		//$description=$sr->GetRowVal($i,2); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */

		/* THIS IS THE LINK TO DETAILS */
		//$sr->ModifyData($i,0,"<a href='index.php?module=survey&task=survey_details&survey_id=".$survey_id."' title='Click to go'>".$survey_id."</a>");

		//$sr->ModifyData($i,2,"<a href='index.php?module=survey&task=survey_questions&survey_id=".$survey_id."' title='Click to go'>Questions</a>");
		$sr->ModifyData($i,2,"<a href='index.php?module=survey&task=survey_questions&subtask=delete&survey_id=".$survey_id."&question_id=".$question_id."' title='Click to delete'>Delete</a>");
	}
	//$sr->RemoveColumn(0);
	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Browse Surveys");

	/* THE FILTER BLOCK */
	//if (ISSET($_POST['survey_id'])) { $survey_id=EscapeData($_POST['survey_id']); } else { $survey_id=""; }
	//$sr->TableFilter("<div align='right'>Survey ID Filter<input type='text' name='survey_id' value='".$survey_id."' size=4></div>","<form name='survey_id_filter' method='post' action='index.php?module=survey&task=".EscapeData($_GET['task'])."'>","</form>");

	$sr->Footer();

	$c.=$sr->Draw();

	/* PLACE THE FOCUS ON THE application ID FILTER */
	$c.="<script language='JavaScript'>\n";
	$c.="document.survey_id_filter.survey_id.focus();\n";
	$c.="</script>\n";

	return $c;
}
?>