<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/sstars/classes/sub_category_id.php";
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
		$obj_ci=new SubCategoryID;
		/* CHECK FOR ADDING */
		if (!ISSET($_POST['sub_category_id']) && EMPTY($_POST['sub_category_id'])) {
			//echo "Adding";
			$result_add=$obj_ci->Add($_POST['sub_category_name'],$_POST['category_id']);
		}
		/* EDITING */
		else {
			//echo "Editing";
			$obj_ci->SetParameters($_POST['sub_category_id']);
			$result_add=$obj_ci->Edit($_POST['sub_category_name']);
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
		if ($_GET['subtask']=="delete" && ISSET($_GET['sub_category_id'])) {
			$obj_ci=new SubCategoryID;
			$obj_ci->SetParameters($_GET['sub_category_id']);
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
	$c.=$tb->DrawBoxes($tab_array,$GLOBALS['dr']."modules/sstars/modules/sub_categories/");

	if (ISSET($_GET['subtask']) && $_GET['subtask'] == "edit" && ISSET($_GET['sub_category_id'])) {
		$c.=$tb->BlockShow("add");
		$c.="<script language=Javascript>document.getElementById('tabbox_add').firstChild.data=\"Edit\";</script>\n";
	}

	return $c;
}
?>