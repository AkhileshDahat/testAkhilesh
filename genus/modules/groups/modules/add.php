<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."classes/form/create_form.php";
require_once $dr."modules/groups/classes/new_group.php";

/* CREATION OF THE IMPORT FORM */
$form=new CreateForm;
$form->SetCredentials("index.php?module=groups&task=add","post","add_group");
$form->Header("crystalclear/48x48/apps/access.png","Adding groups");
$form->Input("Enter a group name","group_name");
$form->Submit("Add Now","FormSubmit");
echo $form->DrawForm();

/* FORM PROCESSING */
if (ISSET($_POST['FormSubmit'])) {
	$ng=new NewGroup;
	$result=$ng->AddGroup($_SESSION['user_id'],$workspace_id,$teamspace_id,$_POST['group_name']);
	if ($result) {
		echo "Success<br>";
	}
	else {
		echo "Failed<br>";
		echo $ng->ShowErrors();
	}
}
?>