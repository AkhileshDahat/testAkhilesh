<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function Subjects($subject_id,$subject_name="") {

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("categories");
	$fv->OpenTag();
	$fv->ValidateFields("subject_name");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=scheduling&task=subjects","post","subjects","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Master Subjects");

	$form->Hidden("subject_id",$subject_id);
	$form->Input("Subject Name","subject_name","","","",$subject_name);

	$form->SetFocus("subject_name");

	$c.=$form->DrawForm();

	return $c;
}
?>