<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/helpdesk/classes/priority_id.php";
require_once $GLOBALS['dr']."modules/helpdesk/functions/forms/priorities.php";

function Add() {

	$c="";

	if (ISSET($_GET['subtask']) && $_GET['subtask'] == "edit" && ISSET($_GET['priority_id'])) {

		$priority_id=EscapeData($_GET['priority_id']);

		$obj_ci=new priorityID;
		$obj_ci->SetParameters($priority_id);

		$priority_name=$obj_ci->GetInfo("priority_name");
	}
	else {
		$priority_id="";
		$priority_name="";
	}

	/* SHOW THE FORM */
	$c.=Priorities($priority_id,$priority_name);

	return $c;
}
?>