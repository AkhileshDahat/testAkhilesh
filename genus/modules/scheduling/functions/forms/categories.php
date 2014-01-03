<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function Categories($category_id,$category_name="") {

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("categories");
	$fv->OpenTag();
	$fv->ValidateFields("category_name");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=helpdesk&task=categories","post","categories","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","helpdesk Categories");

	$form->Hidden("category_id",$category_id);
	$form->Input("Category Name","category_name","","","",$category_name);

	$form->SetFocus("category_name");

	$c.=$form->DrawForm();

	return $c;
}
?>