<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/sstars/functions/forms/sub_categories.php";

function Add() {

	$c="";

	if (ISSET($_GET['sub_category_id']) && $_GET['subtask'] =="edit") {
		$obj_ci=new SubCategoryID;
		$obj_ci->SetParameters($_GET['sub_category_id']);
		$sub_category_name=$obj_ci->GetInfo("sub_category_name");
		$sub_category_id=$_GET['sub_category_id'];
	}
	else {
		$sub_category_name="";
		$sub_category_id="";
	}

	/* SHOW THE FORM */
	$c.=SubCategories($sub_category_name,$sub_category_id);

	return $c;
}
?>