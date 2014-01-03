<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
//require_once $GLOBALS['dr']."classes/form/field_validation.php";

function RecoverPassword() {

	$c="";

	/* FIELD VALIDATION */
	/*
	$fv=new FieldValidation;
	$fv->FormName("recover_password");
	$fv->OpenTag();
	$fv->ValidateFields("email_address");
	$fv->CloseTag();
	$c.=$fv->Draw();
	*/

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=signup&task=recover_password","post","recover_password","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Recover Password");

	$form->BreakCell("Recover your password");
	$form->Input("Email Address","email_address","","","","","25","input_reqd");

	//$form->Submit("Recover","FormSubmit");

	$form->SetFocus("email_address");

	$c.=$form->DrawForm();

	return $c;
}
?>