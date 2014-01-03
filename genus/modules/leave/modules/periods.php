<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/leave/classes/period_id.php";
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
		$obj_pi=new PeriodID;
		/* ADD - NO ID*/
		if (!ISSET($_POST['period_id']) || EMPTY($_POST['period_id'])) {
			//echo "adding";
			$result_modify=$obj_pi->Add($_POST['date_from'],$_POST['date_to']);
		}
		/* EDIT */
		elseif (ISSET($_POST['period_id']) && IS_NUMERIC($_POST['period_id'])) {
			//echo "editing";
			$obj_pi->SetParameters($_POST['period_id']);
			$result_modify=$obj_pi->Edit($_POST['date_from'],$_POST['date_to']);
		}
		if (!$result_modify) {
			$c.="Failed";
			$c.=$obj_pi->ShowErrors();
		}
		else {
			$c.="Success";
		}
	}

	/* DELETE */
	if (ISSET($_GET['subtask'])) {
		if ($_GET['subtask']=="delete" && ISSET($_GET['period_id'])) {
			$obj_pi=new PeriodID;
			$obj_pi->SetParameters($_GET['period_id']);
			$result_del=$obj_pi->Delete();
			if (!$result_del) {
				$c.="Failed";
				$c.=$obj_pi->ShowErrors();
			}
			else {
				$c.="Success";
			}
		}
		elseif ($_GET['subtask']=="active" && ISSET($_GET['period_id'])) {
			$obj_pi=new PeriodID;
			$obj_pi->SetParameters($_GET['period_id']);
			$obj_pi->ChangeActiveStatus();
		}
	}

	/* LAYOUT OF GUI */
	//$tab_array=array("browse","add","history");
	$tab_array=array("browse","add");
	$tb=new TabBoxes;
	$c.=$tb->DrawBoxes($tab_array,$GLOBALS['dr']."modules/leave/modules/period/");

	if (ISSET($_GET['subtask']) && $_GET['subtask'] == "edit" && ISSET($_GET['period_id'])) {
		echo "ok";
		$c.=$tb->BlockShow("add");
	}

	return $c;
}
?>