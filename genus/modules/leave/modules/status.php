<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/leave/classes/status_id.php";
require_once $GLOBALS['dr']."classes/design/tab_boxes.php";

function LoadTask() {

	$c="";

	/*
	**************************************************************************
		PERFORM THE ACTIONS HERE BEFORE WE START DISPLAYING STUFF
	***************************************************************************
	*/
	if (ISSET($_POST['FormSubmit'])) {
		/* CREATE THE OBJECT */
		$obj_si=new StatusID;
		/* ADD - NO ID*/
		if (!ISSET($_POST['status_id']) || EMPTY($_POST['status_id'])) {
			//echo "adding";
			$result_modify=$obj_si->Add($_POST['status_name']);
		}
		/* EDIT */
		elseif (ISSET($_POST['status_id']) && IS_NUMERIC($_POST['status_id'])) {
			//echo "editing";
			$obj_si->SetParameters($_POST['status_id']);
			$result_modify=$obj_si->Edit($_POST['status_name']);
		}
		if (!$result_modify) {
			$c.="Failed";
			$c.=$obj_si->ShowErrors();
		}
		else {
			$c.="Success";
		}
	}

	/* DELETE */
	if (ISSET($_GET['subtask'])) {
		if ($_GET['subtask']=="delete" && ISSET($_GET['status_id'])) {
			$obj_si=new StatusID;
			$obj_si->SetParameters($_GET['status_id']);
			$result_del=$obj_si->Delete();
			if (!$result_del) {
				$c.="Failed";
				$c.=$obj_si->ShowErrors();
			}
			else {
				$c.="Success";
			}
		}
		elseif ($_GET['subtask']=="is_new" && ISSET($_GET['status_id'])) {
			$obj_si=new StatusID;
			$obj_si->SetParameters($_GET['status_id']);
			$obj_si->ChangeIsNew();
		}
		elseif ($_GET['subtask']=="is_new_default" && ISSET($_GET['status_id'])) {
			$obj_si=new StatusID;
			$obj_si->SetParameters($_GET['status_id']);
			$obj_si->ChangeIsNewDefault();
		}
		elseif ($_GET['subtask']=="is_approved" && ISSET($_GET['status_id'])) {
			$obj_si=new StatusID;
			$obj_si->SetParameters($_GET['status_id']);
			$obj_si->ChangeIsApproved();
		}
		elseif ($_GET['subtask']=="is_rejected" && ISSET($_GET['status_id'])) {
			$obj_si=new StatusID;
			$obj_si->SetParameters($_GET['status_id']);
			$obj_si->ChangeIsRejected();
		}
		elseif ($_GET['subtask']=="is_deleted" && ISSET($_GET['status_id'])) {
			$obj_si=new StatusID;
			$obj_si->SetParameters($_GET['status_id']);
			$obj_si->ChangeIsDeleted();
		}
	}

	/* LAYOUT OF GUI */
	//$tab_array=array("browse","add","history");
	$tab_array=array("browse","add");
	$tb=new TabBoxes;
	$c.=$tb->DrawBoxes($tab_array,$GLOBALS['dr']."modules/leave/modules/status/");

	if (ISSET($_GET['subtask']) && $_GET['subtask'] == "edit" && ISSET($_GET['status_id'])) {
		$c.=$tb->BlockShow("add");
	}

	return $c;
}
?>