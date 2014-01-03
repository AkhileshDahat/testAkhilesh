<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function Status($status_id,$status_name="",$is_new="",$is_new_default="",$is_pending_approval="",$is_in_progress="",$is_completed="",$is_closed="",$is_deleted="") {

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("status");
	$fv->OpenTag();
	$fv->ValidateFields("status_name");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=helpdesk&task=status","post","status","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Helpdesk Status");

	$form->Hidden("status_id",$status_id);
	$form->Input("Status Name","status_name","","","",$status_name);

	$arr = array("is_new","is_new_default","is_pending_approval","is_in_progress","is_completed","is_closed","is_deleted");
	foreach ($arr as $a) {
		$desc = str_replace("_"," ",$a);

		$form->Checkbox($desc,$a,$$a);
	}


	//$form->Checkbox("Is New","is_new",$is_new);

	$form->SetFocus("status_name");

	$c.=$form->DrawForm();

	return $c;
}
?>