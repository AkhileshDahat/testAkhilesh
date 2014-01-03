<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";

function AddModule($module_id="",$name="",$description="",$available_teamspaces="",$logo="",$signup_module="") {

	$form=new CreateForm;
	$form->SetCredentials("index.php?module=workspace&task=master_modules","post","add_module");
	$form->Header("crystalclear/48x48/apps/access.png","Master Modules");
	$form->Hidden("module_id",$module_id);

	$form->Input("Module name","name","","","",$name);
	$form->Textarea("Description","description",5,30,$description);
	$form->Checkbox("Available Teamspaces","available_teamspaces",$available_teamspaces);
	$form->Input("Logo","logo","logo","add_module","logo",$logo,40);
	$form->Checkbox("Available Signup","signup_module",$signup_module);

	$form->Submit("Save","FormSubmit");
	return $form->DrawForm();
}

?>