<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/leave/classes/category_id.php";
require_once $GLOBALS['dr']."classes/design/tab_boxes.php";

function LoadTask() {

	$c="";

	/*
	**************************************************************************
		PERFORM THE ACTIONS HERE BEFORE WE START DISPLAYING STUFF
	***************************************************************************
	*/
	/* ADD */
	if (ISSET($_POST['FormSubmit'])) {
		if (ISSET($_POST['allow_negative_balance'])) { $allow_negative_balance="y"; } else { $allow_negative_balance="n"; }
		if (ISSET($_POST['allow_balance_carry_forward'])) { $allow_balance_carry_forward="y"; } else { $allow_balance_carry_forward="n"; }
		$obj_ci=new CategoryID;
		/* CHECK FOR ADDING */
		if (!ISSET($_POST['category_id']) && EMPTY($_POST['category_id'])) {
			//echo "Adding";
			$result_add=$obj_ci->Add($_POST['category_name'],$allow_negative_balance,$allow_balance_carry_forward,$_POST['paid_unpaid']);
		}
		/* EDITING */
		else {
			//echo "Editing";
			$obj_ci->SetParameters($_POST['category_id']);
			$result_add=$obj_ci->Edit($_POST['category_name'],$allow_negative_balance,$allow_balance_carry_forward,$_POST['paid_unpaid']);
		}
		/* SEE THAT IT WAS A SUCCESS */
		if (!$result_add) {
			$c.="Failed";
			$c.=$obj_ci->ShowErrors();
		}
		else {
			$c.="Success";
		}
	}
	/* DELETE */
	if (ISSET($_GET['subtask'])) {
		if ($_GET['subtask']=="delete" && ISSET($_GET['category_id'])) {
			$obj_ci=new CategoryID;
			$obj_ci->SetParameters($_GET['category_id']);
			$result_del=$obj_ci->Delete();
			if (!$result_del) {
				$c.="Failed";
				$c.=$obj_ci->ShowErrors();
			}
			else {
				$c.="Success";
			}
		}
		elseif ($_GET['subtask']=="auto_approve" && ISSET($_GET['category_id'])) {
			$obj_ci=new CategoryID;
			$obj_ci->SetParameters($_GET['category_id']);
			$obj_ci->ChangeAutoApprove();
		}
		elseif ($_GET['subtask']=="planning" && ISSET($_GET['category_id'])) {
			$obj_ci=new CategoryID;
			$obj_ci->SetParameters($_GET['category_id']);
			$obj_ci->ChangePlanning();
		}
	}

	/* LAYOUT OF GUI */
	//$tab_array=array("browse","add","history");
	$tab_array=array("browse","add");
	$tb=new TabBoxes;
	$c.=$tb->DrawBoxes($tab_array,$GLOBALS['dr']."modules/leave/modules/categories/");

	if (ISSET($_GET['subtask']) && $_GET['subtask'] == "edit" && ISSET($_GET['category_id'])) {
		$c.=$tb->BlockShow("add");
		$c.="<script language=Javascript>document.getElementById('tabbox_add').firstChild.data=\"Edit\";</script>\n";
	}

	return $c;
}
?>