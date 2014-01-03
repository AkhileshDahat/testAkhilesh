<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function Significance($significance_id,$significance_name="") {

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("significance");
	$fv->OpenTag();
	$fv->ValidateFields("significance_name");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=helpdesk&task=significance","post","significance","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Helpdesk Significance");

	$form->Hidden("significance_id",$significance_id);
	$form->Input("Significance Name","significance_name","","","",$significance_name);

	$form->SetFocus("significance_name");

	$c.=$form->DrawForm();

	return $c;
}
?>