<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."classes/design/tab_boxes.php";
require_once $GLOBALS['dr']."modules/workspace/classes/master_modules.php";
require_once $GLOBALS['dr']."modules/workspace/functions/browse/master_modules.php";

function LoadTask() {

	$c="";
	/* WE PERFORM THE ACTIONS BEFORE THE INCLUDES */

	/* DELETE */
	if (ISSET($_GET['subtask'])) {
		if ($_GET['subtask']=="delete") {
			$em=new MasterModules;
			$result=$em->Delete($_SESSION['user_id'],$_GET['module_id']);
			if ($result) {
				//$c.=Alert(56);
			}
			else {
				//$c.=Alert(57);
			}
		}
	}

	/* CALL THE FUNCTIONS TO ADD AND EDIT */
	if (ISSET($_POST['FormSubmit'])) {
		$em=new MasterModules;
		if (EMPTY($_POST['module_id'])) { /* NEW RECORD */
			$c.="Saving new...<br>";
			if (ISSET($_POST['popup'])) { $popup=$_POST['popup']; } else { $popup="NULL"; }
			$result=$em->AddError($_POST['module_name'],$_POST['page'],$_POST['description'],$popup,$_POST['alert_type']);
			if ($result) {
				$c.=Alert("49");
			}
			else {
				//$c.="add error<br>";
				$c.=Alert(50,$em->ShowErrors());
			}
		}
		else {
			//$c.="Editing existing...<br>";
			if (ISSET($_POST['available_teamspaces'])) { $available_teamspaces=$_POST['available_teamspaces']; } else { $available_teamspaces=""; } /* SET THE VARIABLE SINCE IT'S A CHECKBOX */
			if (ISSET($_POST['signup_module'])) { $signup_module=$_POST['signup_module']; } else { $signup_module=""; } /* SET THE VARIABLE SINCE IT'S A CHECKBOX */
			$result=$em->Edit($_POST['module_id'],$_POST['name'],$_POST['description'],$available_teamspaces,$_POST['logo'],$signup_module);
			if ($result) {
				$c.=Alert(90);
				//$c.="Success<br>";
			}
			else {
				//$c.="edit error<br>";
				$c.=Alert(91,$em->ShowErrors());
			}
		}
	}

	$c.=CurveBox(MasterModules());

	return $c;
}
?>