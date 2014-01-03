<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."classes/design/tab_boxes.php";
require_once $GLOBALS['dr']."modules/facilities/classes/facility_master.php";

function LoadTask() {
		$c="";

	/* WE PERFORM THE ACTIONS BEFORE THE INCLUDES */

	/* CALL THE FUNCTIONS TO ADD AND EDIT */
	if (ISSET($_GET['subtask']) && $_GET['subtask']=="addedit") {
		$em=new FacilityMaster;

		$result=$em->Add($GLOBALS['ui']->WorkspaceID(),$GLOBALS['ui']->TeamspaceID(),$_POST['facility_name'],$_POST['description'],$_POST['logo'],$_POST['max_user_bookings_week']);
		if ($result) {
			$c.=Alert(92);
		}
		else {
			$c.=Alert(93,$em->ShowErrors());
		}
	}
	elseif (ISSET($_GET['subtask']) && $_GET['subtask']=="edit") {
		//$c.="Editing existing...<br>";
		if (ISSET($_POST['available_teamspaces'])) { $available_teamspaces=$_POST['available_teamspaces']; } else { $available_teamspaces=""; } /* SET THE VARIABLE SINCE IT'S A CHECKBOX */
		if (ISSET($_POST['signup_module'])) { $signup_module=$_POST['signup_module']; } else { $signup_module=""; } /* SET THE VARIABLE SINCE IT'S A CHECKBOX */
		$result=$em->Edit($_POST['facility_id'],$_POST['name'],$_POST['description'],$available_teamspaces,$_POST['logo'],$signup_module);
		if ($result) {
			$c.=Alert(90);
			//$c.="Success<br>";
		}
		else {
			//$c.="edit error<br>";
			$c.=Alert(91,$em->ShowErrors());
		}
	}
	/* DELETE */
	elseif (ISSET($_GET['subtask']) && $_GET['subtask']=="delete") {
		$em=new FacilityMaster;
		$result=$em->Delete($_SESSION['user_id'],$_GET['facility_id']);
		if ($result) {
			//$c.=Alert(56);
		}
		else {
			//$c.=Alert(57);
		}
	}

	/* INCLUDE THE FILES AND PROCESS THEM INTO TABS */
	$tab_array=array("browse","add","times");
	$tb=new TabBoxes();
	$c.=$tb->DrawBoxes($tab_array,$GLOBALS['dr']."modules/facilities/modules/facilities/");
	if (ISSET($_GET['jshow'])) {
		$c.=$tb->BlockShow(EscapeData($_GET['jshow']));
	}

	return $c;
}
?>