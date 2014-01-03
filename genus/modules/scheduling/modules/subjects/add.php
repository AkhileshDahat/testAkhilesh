<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/scheduling/classes/subject_id.php";
require_once $GLOBALS['dr']."modules/scheduling/functions/forms/subjects.php";

function Add() {

	$c="";

	if (ISSET($_GET['subtask']) && $_GET['subtask'] == "edit" && ISSET($_GET['subject_id'])) {

		$subject_id=EscapeData($_GET['subject_id']);

		$obj_ci=new SubjectID;
		$obj_ci->SetParameters($subject_id);

		$subject_name=$obj_ci->GetInfo("subject_name");
	}
	else {
		$subject_id="";
		$subject_name="";
	}

	/* SHOW THE FORM */
	$c.=Subjects($subject_id,$subject_name);

	return $c;
}
?>