<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/scheduling/classes/subject_detail_id.php";
require_once $GLOBALS['dr']."classes/design/tab_boxes.php";

function LoadTask() {

	$c="";

	/*
	**************************************************************************
		PERFORM THE ACTIONS HERE BEFORE WE START DISPLAYING STUFF
	***************************************************************************
	*/
	if (ISSET($_POST['FormSubmit']) && !ISSET($_GET['subtask'])) {
		/* CREATE THE OBJECT */
		$obj_ci=new SubjectDetailID;
		/* ADD - NO ID*/
		if (!ISSET($_POST['subject_detail_id']) || EMPTY($_POST['subject_detail_id'])) {

			$result_modify=$obj_ci->Add($_POST['subject_id'],$_POST['user_id'],$_POST['duration_hours'],$_POST['date_start'],$_POST['date_end'],$_POST['capacity'],$_POST['description'],$_POST['resource_item_reqs']);
			/* ADD THE ITEMS THAT THIS BOOKING NEEDS FROM THE ARRAY OF CHECKBOXES */
			$obj_ci->InsertItemReqs($_POST['resource_item_reqs']);
		}
		/* EDIT */
		elseif (ISSET($_POST['subject_detail_id']) && IS_NUMERIC($_POST['subject_detail_id'])) {
			$obj_ci->SetParameters($_POST['subject_detail_id']);
			$result_modify=$obj_ci->Edit($_POST['subject_name']);
		}
		if (!$result_modify) {
			$c.="Failed";
			$c.=$obj_ci->ShowErrors();
		}
		else {
			$c.="Success";
		}
	}

	/* DELETE */
	if (ISSET($_GET['subtask'])) {

		if ($_GET['subtask']=="delete" && ISSET($_GET['subject_detail_id'])) {
			$obj_sdi=new SubjectDetailID;
			$obj_sdi->SetParameters($_GET['subject_detail_id']);
			$result_del=$obj_sdi->Delete();
			if (!$result_del) {
				$c.="Failed";
				$c.=$obj_sdi->ShowErrors();
			}
			else {
				$c.="Success";
			}
		}
	}

	/* LAYOUT OF GUI */
	//$tab_array=array("browse","add","history");
	//$tab_array=array("browse","add");
	$tab_array=array("add","browse");
	$tb=new TabBoxes;
	$c.=$tb->DrawBoxes($tab_array,$GLOBALS['dr']."modules/scheduling/modules/subject_detail/");

	if (ISSET($_GET['subtask']) && $_GET['subtask'] == "edit" && ISSET($_GET['subject_detail_id'])) {
		$c.=$tb->BlockShow("add");
		$c.="<script language=Javascript>document.getElementById('tabbox_add').firstChild.data=\"Edit\";</script>\n";
	}

	return $c;
}
?>