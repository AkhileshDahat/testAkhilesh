<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function Roles($role_id,$role_name="") {

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("roles");
	$fv->OpenTag();
	$fv->ValidateFields("role_name");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=core&task=roles","post","roles","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","System roles");

	$form->Hidden("role_id",$role_id);
	$form->Input("Role Name","role_name","","","",$role_name);

	$form->SetFocus("role_name");

	$c.=$form->DrawForm();

	return $c;
}
?>