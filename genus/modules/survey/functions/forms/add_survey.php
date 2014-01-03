<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function Addsurvey() {

	/* INCLUDE THE JCALENDAR */
	$GLOBALS['head']->IncludeFile("jscalendar");

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("apply");
	$fv->OpenTag();
	$fv->ValidateFields("account_name");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=survey&task=add_survey","post","apply","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Add survey");

	$form->Textarea("Description","description",5,30,"");
	$form->BreakCell("Survey Active Period");
	$form->Date("Open Date","date_open","");
	$form->Date("Close Date","date_closed","");

	$form->SetFocus("description");

	$c.=$form->DrawForm();

	return $c;
}
?>