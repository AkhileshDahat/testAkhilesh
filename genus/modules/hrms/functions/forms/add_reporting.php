<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."classes/form/create_form.php";

//require_once $GLOBALS['dr']."include/functions/db/get_col_value.php";

require_once $GLOBALS['dr']."modules/hrms/classes/user_reporting.php";

function AddReporting($subordinate_user_id="",$superior_user_id="") {
	$db=$GLOBALS['db'];
	$database_prefix=$GLOBALS['database_prefix'];
	$wb=$GLOBALS['wb'];

	$c="";

	$show_form=True;

	if (ISSET($_POST['subordinate_user_id']) && ISSET($_POST['superior_user_id'])) {

		$ur=new UserReporting;
		//echo $_POST['superior_user_id']."<br>";
		$ur->SetParameters($_POST['subordinate_user_id']);
		$result=$ur->Add($_POST['superior_user_id']);
		if ($result) {
			$c.=ErrorPageTop("success","Success");
			//$show_form=False;
		}
		else {
			$c.=ErrorPageTop("fail",$ur->ShowErrors());
		}
	}

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=hrms&task=user_reporting","post","add_reporting","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Account");

	if (ISSET($_POST['subordinate_user_id'])) { $subordinate_user_id=$_POST['subordinate_user_id']; } else { $subordinate_user_id=""; }
	$sql = "SELECT *
					FROM core_user_master cm, core_space_users cus
					WHERE cm.user_id = cus.user_id
					AND cus.workspace_id = ".$GLOBALS['workspace_id'];
	$form->ShowDropDown("Subordinate","user_id","full_name","core_user_master","subordinate_user_id",$subordinate_user_id,$sql,"","","input_reqd");
	if (ISSET($_POST['subordinate_user_id'])) {
		$subordinate_user_id=GetColumnValue("superior_user_id","hrms_user_reporting","subordinate_user_id",$subordinate_user_id,"");
		$form->ShowDropDown("Superior","user_id","full_name","core_user_master","superior_user_id",$subordinate_user_id,"","","","input_reqd");
	}
	if ($show_form) {
		$c.=$form->DrawForm();
	}
	return $c;
}
?>