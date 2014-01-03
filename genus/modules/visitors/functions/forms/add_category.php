<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."classes/form/create_form.php";

function AddCategory($category_id="",$category_name="") {
	$db=$GLOBALS['db'];
	$database_prefix=$GLOBALS['database_prefix'];
	$wb=$GLOBALS['wb'];

	$c="";

	$show_form=True;

	if (ISSET($_POST['category_id'])) {

		$obj_ci=new CategoryID;
		//echo $_POST['category_id']."<br>";
		$obj_ci->SetParameters($_POST['category_id']);
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
	$form->SetCredentials("index.php?module=visitors&task=categories","post","add_category","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Categories");
	$form->Input("Category","category_name","","","",$category_name);
	$form->Hidden("category_id",$category_id);
	//$form->ShowDropDown("Category","category_id","category_name","visitor_category_master","category_id",$category_id,"","WHERE workspace_id = ".$GLOBALS['workspace_id'],"","input_reqd");
	
	if ($show_form) {
		$c.=$form->DrawForm();
	}
	return $c;
}
?>