<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/documents/functions/browse/category_approvers.php";
require_once $GLOBALS['dr']."modules/documents/classes/category_approvers.php";

function LoadTask() {

	/* FORM PROCESSING */

	if (ISSET($_POST['submit'])) {

		/* GET THE CATEGORY FROM THE QUERYSTRING */
		$v_category_id=$_GET['category_id'];

		if (ISSET($_POST['select1'])) {

			/* ADD */
			$v_user_id_add_arr=$_POST['select1'];
			for ($i=0;$i<count($v_user_id_add_arr);$i++) {
				$v_user_id=EscapeData($v_user_id_add_arr[$i]);
				$cs=new CategoryApprovers;
				$cs->SetParameters($v_category_id,$v_user_id);
				$cs->Add();
			}
		}
		/* DELETE */
		if (ISSET($_POST['select2'])) {
			$v_user_id_del_arr=$_POST['select2'];
			for ($i=0;$i<count($v_user_id_del_arr);$i++) {
				$v_user_id=EscapeData($v_user_id_del_arr[$i]);
				$cs=new CategoryApprovers;
				$cs->SetParameters($v_category_id,$v_user_id);
				$cs->Delete();
			}
		}
	}

	/* QUERYSTRING PROCESSING */
	if (ISSET($_GET['category_id'])) {
		$v_category_id=$_GET['category_id'];
	}
	else {
		$v_category_id=null;
	}

	/* SHOW THE FORM */
	return CategoryApprovers($v_category_id);
}
?>