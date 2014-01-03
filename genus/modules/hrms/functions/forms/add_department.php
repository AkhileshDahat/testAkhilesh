<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."classes/form/create_form.php";

require_once $GLOBALS['dr']."modules/hrms/classes/user_department.php";

function AddDepartment($user_id="",$department_id="") {
	$db=$GLOBALS['db'];
	$database_prefix=$GLOBALS['database_prefix'];
	$wb=$GLOBALS['wb'];

	$c="";

	$show_form=True;

	if (ISSET($_POST['user_id']) && ISSET($_POST['department_id'])) {

		$ur=new UserDepartment;
		//echo $_POST['department_id']."<br>";
		$ur->SetParameters($_POST['user_id'],$_POST['department_id']);
		$result=$ur->Add();
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
	$form->SetCredentials("index.php?module=hrms&task=user_department","post","add_department","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Account");

	if (ISSET($_POST['user_id'])) { $user_id=$_POST['user_id']; } else { $user_id=""; }
	$form->ShowDropDown("User","user_id","full_name","core_user_master","user_id",$user_id,"","WHERE workspace_id = ".$GLOBALS['workspace_id'],"","input_reqd");
	if (ISSET($_POST['user_id'])) {
		$department_id=GetColumnValue("department_id","hrms_user_department","user_id",$user_id,"");
		$form->ShowDropDown("Department","department_id","department_name","hrms_department_master","department_id",$department_id,"","WHERE workspace_id = ".$GLOBALS['workspace_id'],"","input_reqd");
	}
	if ($show_form) {
		$c.=$form->DrawForm();
	}
	return $c;
}
?>