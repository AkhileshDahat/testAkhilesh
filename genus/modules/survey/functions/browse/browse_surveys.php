<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");
require_once($GLOBALS['dr']."modules/documents/classes/category_id.php");

function BrowseSurveys($p_filter="") {

	$c="";

	/* FILTER VARIOUS VIEWS OF ALL THE APPLICATIONS */
	if ($p_filter=="my") { $sql_extra="AND la.user_id = ".$_SESSION['user_id']; }
	else { $sql_extra=""; }

	/* FILTER THE APPLICATION ID */
	if (ISSET($_POST['survey_id']) && IS_NUMERIC($_POST['survey_id'])) { $sql_survey_id="AND sm.survey_id = ".EscapeData($_POST['survey_id']); } else { $sql_survey_id=""; }

	$sr=new ShowResults;
	$sr->SetParameters(True);

	$sr->DrawFriendlyColHead(array("","Name","Description","Date Open","Date Closed","Add Questions","Results")); /* COLS */
	$sr->Columns(array("survey_id","full_name","description","date_open","date_closed","add_questions","results"));

	$sql="SELECT sm.survey_id,um.full_name,sm.description,sm.date_open,sm.date_closed,'add_questions' as add_questions,'results' as results
							FROM ".$GLOBALS['database_prefix']."survey_master sm,".$GLOBALS['database_prefix']."core_user_master um
							WHERE sm.workspace_id = ".$GLOBALS['workspace_id']."
							AND sm.teamspace_id ".$GLOBALS['teamspace_sql']."
							AND sm.user_id = um.user_id
							$sql_extra
							$sql_survey_id
							ORDER BY survey_id DESC
							";
	//echo $sql;
	$sr->Query($sql);
	for ($i=0;$i<$sr->CountRows();$i++) {
		$survey_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$description=$sr->GetRowVal($i,2); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */

		/* THIS IS THE LINK TO DETAILS */
		//$sr->ModifyData($i,0,"<a href='index.php?module=survey&task=survey_details&survey_id=".$survey_id."' title='Click to go'>".$survey_id."</a>");
		$sr->ModifyData($i,2,"<a href='index.php?module=survey&task=survey_details&survey_id=".$survey_id."' title='Click to go'>".$description."</a>");
		$sr->ModifyData($i,5,"<a href='index.php?module=survey&task=survey_questions&survey_id=".$survey_id."' title='Click to go'>Questions</a>");
		$sr->ModifyData($i,6,"<a href='index.php?module=survey&task=reports&subtask=survey_question_average&survey_id=".$survey_id."' title='Click to go'>Results</a>");
	}
	//$sr->RemoveColumn(0);
	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Browse Surveys");

	/* THE FILTER BLOCK */
	if (ISSET($_POST['survey_id'])) { $survey_id=EscapeData($_POST['survey_id']); } else { $survey_id=""; }
	$sr->TableFilter("<div align='right'>Survey ID Filter<input type='text' name='survey_id' value='".$survey_id."' size=4></div>","<form name='survey_id_filter' method='post' action='index.php?module=survey&task=".EscapeData($_GET['task'])."'>","</form>");

	$sr->Footer();

	$c.=$sr->Draw();

	/* PLACE THE FOCUS ON THE application ID FILTER */
	$c.="<script language='JavaScript'>\n";
	$c.="document.survey_id_filter.survey_id.focus();\n";
	$c.="</script>\n";

	return $c;
}
?>