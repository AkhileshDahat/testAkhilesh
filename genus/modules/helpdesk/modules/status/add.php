<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/helpdesk/classes/status_id.php";
require_once $GLOBALS['dr']."modules/helpdesk/functions/forms/status.php";

function Add() {

	$c="";

	if (ISSET($_GET['subtask']) && $_GET['subtask'] == "edit" && ISSET($_GET['status_id'])) {

		$status_id=EscapeData($_GET['status_id']);

		$obj_ci=new StatusID;
		$obj_ci->SetParameters($status_id);

		$status_name=$obj_ci->GetInfo("status_name");
		$is_new=$obj_ci->GetInfo("is_new");
		$is_new_default=$obj_ci->GetInfo("is_new_default");
		$is_pending_approval=$obj_ci->GetInfo("is_pending_approval");
		$is_in_progress=$obj_ci->GetInfo("is_in_progress");
		$is_completed=$obj_ci->GetInfo("is_completed");
		$is_closed=$obj_ci->GetInfo("is_closed");
		$is_deleted=$obj_ci->GetInfo("is_deleted");
	}
	else {
		$status_id="";
		$status_name="";
		$is_new="";
		$is_new_default="";
		$is_pending_approval="";
		$is_in_progress="";
		$is_completed="";
		$is_closed="";
		$is_deleted="";
	}

	/* SHOW THE FORM */
	$c.=Status($status_id,$status_name,$is_new,$is_new_default,$is_pending_approval,$is_in_progress,$is_completed,$is_closed,$is_deleted);

	return $c;
}
?>