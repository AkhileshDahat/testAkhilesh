<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");
require_once($GLOBALS['dr']."include/functions/filesystem/size_int.php");

function SubTask() {
	$c="";
	if (ISSET($_GET['survey_id'])) {
		$survey_id=EscapeData($_GET['survey_id']);
		/*
		$sr=new ShowResults;
		$sr->SetParameters(True);
		$sr->DrawFriendlyColHead(array("Question","Average"));
		$sr->Columns(array("question","question_avg"));
		*/
		$sql="SELECT sam.question_id,sqm.question,ROUND(AVG(sam.answer),2) as question_avg
					FROM ".$GLOBALS['database_prefix']."survey_answer_master sam,".$GLOBALS['database_prefix']."survey_question_master sqm
					WHERE survey_id = '".$survey_id."'
					AND sam.question_id = sqm.question_id
					GROUP BY sam.question_id
					ORDER BY sam.question_id
								";
		//echo $sql;
		$db=$GLOBALS['db'];
		$result=$db->query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$c.="<img src='modules/survey/bin/survey_question_bar_chart.php?survey_id=".$survey_id."&question_id=".$row['question_id']."'><br />";
			}
		}


		/*
		$sr->Query($sql);
		$sr->WrapData();
		$sr->TableTitle("nuvola/22x22/actions/gohome.png","All Question Report");
		return $sr->Draw();
		*/
		return $c;
	}
	else {
		return "Please select the correct survey";
	}
}
?>