<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function Tags($tag_id,$tag_name="") {

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("categories");
	$fv->OpenTag();
	$fv->ValidateFields("tag_name");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=helpdesk&task=tags","post","tags","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Helpdesk Tags");

	$form->Hidden("tag_id",$tag_id);
	$form->Input("Tag Name","tag_name","","","",$tag_name);

	$form->SetFocus("tag_name");

	$c.=$form->DrawForm();

	return $c;
}
?>