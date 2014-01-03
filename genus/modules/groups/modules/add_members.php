<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."classes/form/create_form.php"; /* FORM CREATION CLASS */
require_once $dr."modules/groups/classes/new_group_member.php"; /* CLASS TO ADD NEW MEMBER */
require_once $dr."modules/groups/functions/browse/group_members.php"; /* BROWSE THE GROUP MEMBERS */

/* VARIABLES FROM THE STRING */
if (ISSET($_GET['group_id'])) { $group_id=$_GET['group_id']; } else { $group_id=""; }
if (ISSET($_POST['group_id'])) { $group_id=$_POST['group_id']; }

/* CREATION OF THE IMPORT FORM */
$form=new CreateForm;
$form->SetCredentials("index.php?module=groups&task=add_members","post","add_group_members");
$form->Header("crystalclear/48x48/apps/access.png","Adding members to your group");
$form->Input("Select user","user_name","user","add_group_members","user_name","user_id");
$form->Hidden("user_id"); /* THIS COMES FROM THE POPUP FORM */
$form->Hidden("group_id",$group_id); /* THIS IS THE GROUP WE'RE ADDING TO */
$form->Submit("Add Now","FormSubmit");
echo $form->DrawForm();

/* FORM PROCESSING */
if (ISSET($_POST['FormSubmit'])) {
	$ngm=new NewGroupMember;

	$result=$ngm->AddGroupMember($_POST['group_id'],$_POST['user_id']);
	if ($result) {
		echo "Success<br>";
	}
	else {
		echo "Failed<br>";
		echo $ngm->ShowErrors();
	}
}

echo GroupMembers($_SESSION['user_id'],$workspace_id,$teamspace_id,$group_id);

?>