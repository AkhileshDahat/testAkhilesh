<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/scheduling/classes/subject_detail_id.php";
require_once $GLOBALS['dr']."modules/scheduling/functions/forms/subject_detail.php";

function Add() {

	$c="";

	if (ISSET($_GET['subtask']) && $_GET['subtask'] == "edit" && ISSET($_GET['subject_detail_id'])) {

		$subject_detail_id=EscapeData($_GET['subject_detail_id']);

		$obj_ci=new SubjectDetailID;
		$obj_ci->SetParameters($subject_detail_id);

		$subject_id=$obj_ci->GetInfo("subject_id");
		$user_id=$obj_ci->GetInfo("user_id");
		$duration_hours=$obj_ci->GetInfo("duration_hours");
		$date_start=$obj_ci->GetInfo("date_start");
		$date_end=$obj_ci->GetInfo("date_end");
		$capacity=$obj_ci->GetInfo("capacity");
		$description=$obj_ci->GetInfo("description");
		$resources_item_reqs=$obj_ci->GetInfo("resources_item_reqs");

	}
	else {
		$subject_detail_id="";
		$subject_id="";
		$user_id="";
		$duration_hours="";
		$date_start="";
		$date_end="";
		$capacity="";
		$description="";
		$resources_item_reqs="";
	}

	/* SHOW THE FORM */
	$c.=SubjectDetail($subject_detail_id,$subject_id,$user_id,$duration_hours,$date_start,$date_end,$capacity,$description,$resources_item_reqs);

	return $c;
}
?>