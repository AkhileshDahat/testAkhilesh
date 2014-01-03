<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/sstars/functions/forms/categories.php";

function Add() {

	$c="";

	if (ISSET($_GET['category_id']) && $_GET['subtask'] =="edit") {
		$obj_ci=new CategoryID;
		$obj_ci->SetParameters($_GET['category_id']);
		$category_name=$obj_ci->GetInfo("category_name");
		$category_id=$_GET['category_id'];
	}
	else {
		$category_name="";
		$category_id="";
	}

	/* SHOW THE FORM */
	$c.=Categories($category_name,$category_id);

	return $c;
}
?>