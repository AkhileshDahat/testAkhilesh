<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/hrms/classes/department_id.php";
require_once $GLOBALS['dr']."modules/hrms/functions/forms/departments.php";

function Add() {

	$c="";

	if (ISSET($_GET['subtask']) && $_GET['subtask'] == "edit" && ISSET($_GET['department_id'])) {

		$department_id=EscapeData($_GET['department_id']);

		$obj_ci=new departmentID;
		$obj_ci->SetParameters($department_id);

		$department_name=$obj_ci->GetInfo("department_name");
	}
	else {
		$department_id="";
		$department_name="";
	}

	/* SHOW THE FORM */
	$c.=departments($department_id,$department_name);

	return $c;
}
?>