<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function FormSurveyQuestions($survey_id) {
	
	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("form_survey_questions");
	$fv->OpenTag();
	$fv->ValidateFields("question");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=survey&task=survey_questions&survey_id=".$survey_id,"post","form_survey_questions","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Add survey questions");

	$form->Textarea("Question","question",5,30,"");

	$form->Hidden("survey_id",$survey_id);

	$form->SetFocus("question");

	$c.=$form->DrawForm();

	return $c;
}
?>