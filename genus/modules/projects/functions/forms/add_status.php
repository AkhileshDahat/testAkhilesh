<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."classes/form/create_form.php";

function AddStatus($status_id="",$status_name="") {

	$db=$GLOBALS['db'];
	$database_prefix=$GLOBALS['database_prefix'];
	$wb=$GLOBALS['wb'];

	$c="";

	$show_form=True;

	if (ISSET($_POST['status_id'])) {

		$obj_ci=new StatusID;
		//echo $_POST['status_id']."<br>";
		$obj_ci->SetParameters($_POST['status_id']);
		$result=$obj_ci->Add();
		if ($result) {
			$c.=ErrorPageTop("success","Success");
			//$show_form=False;
		}
		else {
			$c.=ErrorPageTop("fail",$obj_ci->ShowErrors());
		}
	}

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=projects&task=status","post","add_status","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Status");
	$form->Input("Status","status_name","","","",$status_name);
	$form->Hidden("status_id",$status_id);
		
	if ($show_form) {
		$c.=$form->DrawForm();
	}
	return $c;
}
?>