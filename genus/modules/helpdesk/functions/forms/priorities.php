<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function Priorities($priority_id,$priority_name="") {

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("priorities");
	$fv->OpenTag();
	$fv->ValidateFields("priority_name");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=helpdesk&task=priorities","post","priorities","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Helpdesk Priorities");

	$form->Hidden("priority_id",$priority_id);
	$form->Input("Priority Name","priority_name","","","",$priority_name);

	$form->SetFocus("priority_name");

	$c.=$form->DrawForm();

	return $c;
}
?>