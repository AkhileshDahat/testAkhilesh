<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function SubCategories($sub_category_name="",$category_id,$sub_category_id="") {

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("sub_categories");
	$fv->OpenTag();
	$fv->ValidateFields("sub_category_name");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=sstars&task=sub_categories","post","sub_categories","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Sub Categories");
	if (!EMPTY($sub_category_id)) {
		$form->Hidden("sub_category_id",$sub_category_id);
	}
	$form->Input("Sub Category Name","sub_category_name","","","",$sub_category_name);
	$form->ShowDropDown("Category","category_id","category_name","sstars_category_master","category_id",$category_id,"","WHERE workspace_id = ".$GLOBALS['workspace_id'],"","input_reqd");
	//$form->Checkbox("Allow negative balance","allow_negative_balance",$allow_negative_balance);


	$form->SetFocus("category_name");

	$c.=$form->DrawForm();

	return $c;
}
?>