<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/helpdesk/classes/significance_id.php";
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
		$obj_ci=new SignificanceID;
		/* ADD - NO ID*/
		if (!ISSET($_POST['significance_id']) || EMPTY($_POST['significance_id'])) {
			//echo "adding";
			$result_modify=$obj_ci->Add($_POST['significance_name']);
		}
		/* EDIT */
		elseif (ISSET($_POST['significance_id']) && IS_NUMERIC($_POST['significance_id'])) {
			//echo "editing";
			$obj_ci->SetParameters($_POST['significance_id']);
			$result_modify=$obj_ci->Edit($_POST['significance_name']);
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
		if ($_GET['subtask']=="delete" && ISSET($_GET['significance_id'])) {
			$obj_ci=new SignificanceID;
			$obj_ci->SetParameters($_GET['significance_id']);
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
	//$tab_array=array("browse","add","history");
	$tab_array=array("browse","add");
	$tb=new TabBoxes;
	$c.=$tb->DrawBoxes($tab_array,$GLOBALS['dr']."modules/helpdesk/modules/significance/");

	if (ISSET($_GET['subtask']) && $_GET['subtask'] == "edit" && ISSET($_GET['significance_id'])) {
		echo "ok";
		$c.=$tb->BlockShow("add");
		//$c.="<script language=Javascript>document.getElementById('Head_browse').firstChild.data=\"Edit\";</script>\n";
		//$c.="<script language=Javascript>document.getElementById('Head_browse').value=\"Edit\";</script>\n";
	}

	return $c;
}
?>