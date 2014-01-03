<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/helpdesk/classes/category_id.php";
require_once $GLOBALS['dr']."modules/helpdesk/functions/forms/categories.php";

function Add() {

	$c="";

	if (ISSET($_GET['subtask']) && $_GET['subtask'] == "edit" && ISSET($_GET['category_id'])) {

		$category_id=EscapeData($_GET['category_id']);

		$obj_ci=new CategoryID;
		$obj_ci->SetParameters($category_id);

		$category_name=$obj_ci->GetInfo("category_name");
	}
	else {
		$category_id="";
		$category_name="";
	}

	/* SHOW THE FORM */
	$c.=Categories($category_id,$category_name);

	return $c;
}
?>