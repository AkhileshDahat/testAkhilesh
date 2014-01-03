<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/projects/classes/project_id.php";
require_once $GLOBALS['dr']."modules/projects/functions/forms/add_status.php";

function Add() {

	$c="";

	if (ISSET($_GET['subtask']) && $_GET['subtask'] == "edit" && ISSET($_GET['status_id'])) {

		/* QUERY THE OBJECT FOR SENDING TO THE FORM */
		$status_id=EscapeData($_GET['status_id']);
		$obj_ci=new StatusID;
		$obj_ci->SetParameters($status_id);
		$status_name=$obj_ci->GetInfo("status_name");		
	}
	else {
		$status_id="";
		$status_name="";
	}

	/* SHOW THE FORM */
	$c.=AddStatus($status_id,$status_name);

	return $c;
}
?>