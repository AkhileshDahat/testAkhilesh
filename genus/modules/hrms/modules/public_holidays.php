<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/hrms/classes/public_holiday.php";
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
		$obj_ph=new PublicHoliday;
		/* ADD - NO ID*/
		if (ISSET($_POST['date_pub_hol'])) {
			$result_modify=$obj_ph->Add($_POST['date_pub_hol']);
		}

		if (!$result_modify) {
			$c.="Failed";
			$c.=$obj_ph->ShowErrors();
		}
		else {
			$c.="Success";
		}
	}

	/* DELETE */
	if (ISSET($_GET['subtask'])) {
		if ($_GET['subtask']=="delete" && ISSET($_GET['date_pub_hol'])) {
			$obj_ph=new PublicHoliday;
			$obj_ph->SetParameters($_GET['date_pub_hol']);
			$result_del=$obj_ph->Delete();
			if (!$result_del) {
				$c.="Failed";
				$c.=$obj_ph->ShowErrors();
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
	$c.=$tb->DrawBoxes($tab_array,$GLOBALS['dr']."modules/hrms/modules/public_holidays/");

	//if (ISSET($_GET['subtask']) && $_GET['subtask'] == "edit" && ISSET($_GET['date_pub_hol'])) {
		//$c.=$tb->BlockShow("add");
		//$c.="<script language=Javascript>document.getElementById('tabbox_add').firstChild.data=\"Edit\";</script>\n";
	//}

	return $c;
}
?>