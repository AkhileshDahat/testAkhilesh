	<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";
require_once $GLOBALS['dr']."modules/survey/classes/survey_id.php";

function TakeSurvey() {

	/* INCLUDE THE AJAX FILE */
	$GLOBALS['head']->IncludeFile("ajaxgold");

	$c="";

	if (!ISSET($_SESSION['survey_password'])) {

		/* DESIGN THE FORM FOR ENTERING THE PASSWORD */
		$form=new CreateForm;
		$form->SetCredentials("index.php?module=survey&task=take_survey","post","apply","onSubmit=\"ValidateForm(this)\"");
		$form->Header("crystalclear/48x48/apps/access.png","Take survey");

		$form->Input("Password","password","","","","",8,"","autocomplete=off");

		$form->SetFocus("password");

		$c.=$form->DrawForm();
	}
	elseif (ISSET($_SESSION['survey_id'])) {

		$c.="<form method='post' action='index.php?module=survey&task=take_survey'>\n";

		$c.="<script language='Javascript'>\n";
		$c.="function DisableRadioRow(v) {\n";
			$c.="for (var i=1;i<=5;i++) {;\n";
				$c.="var radi = 'answer_'+v+'_'+i;\n";
				$c.="document.getElementById(radi).disabled=true;\n";
			$c.="}\n";
		$c.="}\n";
		$c.="</script>\n";


		/* DISPLAY THE SURVEY */
		$sql="SELECT question_id,question
					FROM ".$GLOBALS['database_prefix']."survey_question_master sqm
					WHERE survey_id = '".$_SESSION['survey_id']."'
					";
		//echo $sql."<br>";
		$result = mysql_query($sql);
		if (!$result) { die('Invalid query: ' . mysql_error()); }
		$c.="<table border=1 class='plain'>\n";
			$c.="<tr>\n";
				$c.="<td colspan='6' class='colhead'>Answer the survey</td>\n";
			$c.="</tr>\n";
			$c.="<tr>\n";
				$arr=array("","Completely Disagree","Disagree","Neutral","Agree","Definately Agree");
				for ($i=0;$i<count($arr);$i++) {
					$c.="<td align='center'>".$arr[$i]."</td>\n";
				}
			$c.="</tr>\n";

		while ($row = mysql_fetch_array($result)) {
	    $c.="<tr>\n";
	    	$c.="<td>".$row['question']."</td>";
	    	for ($i=1;$i<6;$i++) {
	    		$c.="<td align='center'><input name='answer_".$row['question_id']."' id='answer_".$row['question_id']."_".$i."' type='radio' value='".$i."' onClick=\"getDataReturnText('modules/survey/ajax/save_survey.php?question_id=".$row['question_id']."&answer=".$i."','SavePrompt');DisableRadioRow(".$row['question_id'].");\"></td>\n";
	    	}
	    $c.="</tr>\n";
		}
		/*
		$c.="<tr>\n";
			$c.="<td colspan='6' class='colhead'><input type='submit' name='FormSubmit1' value='Save Survey' class='buttonstyle'></td>\n";
		$c.="</tr>\n";
		*/
		$c.="</table>\n";
		$c.="</form>\n";

	}
	else {
		unset($_SESSION['survey_password']);
		unset($_SESSION['survey_id']);
	}

	return $c;

}
?>