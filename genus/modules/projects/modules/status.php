<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/projects/classes/status_id.php";
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
		$obj_ci=new StatusID;
		/* ADD - NO ID*/
		if (!ISSET($_POST['status_id']) || EMPTY($_POST['status_id'])) {
			$result_modify=$obj_ci->GetFormPostedValues();
			$result_modify=$obj_ci->Add();
		}
		/* EDIT */
		elseif (ISSET($_POST['status_id']) && IS_NUMERIC($_POST['status_id'])) {
			$obj_ci->SetParameters($_POST['status_id']);
			$result_modify=$obj_ci->Edit($_POST['status_name']);
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
		if ($_GET['subtask']=="delete" && ISSET($_GET['status_id'])) {
			$obj_ci=new StatusID;
			$obj_ci->SetParameters($_GET['status_id']);
			$result_del=$obj_ci->Delete();
			if (!$result_del) {
				$c.="Failed";
				$c.=$obj_ci->ShowErrors();
			}
			else {
				$c.="Success";
			}
		}
	}
	
	/* LAYOUT OF GUI */	
	$tab_array=array("browse","add");
	$tb=new TabBoxes;
	$c.=$tb->DrawBoxes($tab_array,$GLOBALS['dr']."modules/projects/modules/status/");

	if (ISSET($_GET['subtask']) && $_GET['subtask'] == "edit" && ISSET($_GET['status_id'])) {
		$c.=$tb->BlockShow("add");
		$c.="<script language=Javascript>document.getElementById('tabbox_add').firstChild.data=\"Edit\";</script>\n";
	}

	return $c;
}
?>