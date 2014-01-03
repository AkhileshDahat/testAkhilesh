<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";

function NewTeamspace() {

	$c="";
	$GLOBALS['head']->IncludeFile("jscalendar");

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=teamspace&task=new_teamspace","post","new_teamspace","onSubmit=\"ValidateForm(this)\"");
	$form->Header("../modules/teamspace/images/default/new_teamspace.png","New Teamspace");

	$form->BreakCell("Teamspace Information");
	$form->Input("Teamspace Name:","name","");
	$form->InformationCell("Teamspace names must be unique within the workspace");
	$form->Textarea("Description","description","","20");
	$form->Date("Available From","date_valid_from","");
	$form->InformationCell("Teamspace is only available from this date");
	$form->Date("Available To","date_valid_to","");
	$form->InformationCell("Teamspace expires on this date");

	$c.=$form->DrawForm();

	return $c;
}
?>