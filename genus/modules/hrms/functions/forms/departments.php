<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function Departments($department_id,$department_name="") {

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("Departments");
	$fv->OpenTag();
	$fv->ValidateFields("department_name");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=hrms&task=departments","post","Departments","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Departments");

	$form->Hidden("department_id",$department_id);
	$form->Input("Department Name","department_name","","","",$department_name);

	$form->SetFocus("department_name");

	$c.=$form->DrawForm();

	return $c;
}
?>