<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/documents/functions/browse/category_role_security.php";
require_once $GLOBALS['dr']."modules/documents/functions/browse/category_user_security.php";
require_once $GLOBALS['dr']."modules/documents/classes/category_role_security.php";
require_once $GLOBALS['dr']."modules/documents/classes/category_user_security.php";

function LoadTask() {

	/* FORM PROCESSING */

	if (ISSET($_POST['submit'])) {

		/* GET THE CATEGORY FROM THE QUERYSTRING */
		$v_category_id=$_GET['category_id'];

		/* DO ALL THE ROLE PROCESSING */
		if (ISSET($_POST['submit']) && $_POST['submit']=="Apply Role Changes") {
			if (ISSET($_POST['select1'])) {
				/* ADD */
				$v_role_id_add_arr=$_POST['select1'];
				for ($i=0;$i<count($v_role_id_add_arr);$i++) {
					$v_role_id=EscapeData($v_role_id_add_arr[$i]);
					$cs=new CategoryRoleSecurity;
					$cs->SetParameters($v_category_id,$v_role_id);
					$cs->Add();
				}
			}
			/* DELETE */
			else if (ISSET($_POST['select2'])) {
				$v_role_id_del_arr=$_POST['select2'];
				for ($i=0;$i<count($v_role_id_del_arr);$i++) {
					$v_role_id=EscapeData($v_role_id_del_arr[$i]);
					$cs=new CategoryRoleSecurity;
					$cs->SetParameters($v_category_id,$v_role_id);
					$cs->Delete();
				}
			}
		}
		/* DO ALL THE USER PROCESSING */
		if (ISSET($_POST['submit']) && $_POST['submit']=="Apply User Changes") {
			if (ISSET($_POST['select1'])) {
				/* ADD */
				$v_user_id_add_arr=$_POST['select1'];
				for ($i=0;$i<count($v_user_id_add_arr);$i++) {
					$v_user_id=EscapeData($v_user_id_add_arr[$i]);
					$cs=new CategoryUserSecurity;
					$cs->SetParameters($v_category_id,$v_user_id);
					$cs->Add();
				}
			}
			/* DELETE */
			else if (ISSET($_POST['select2'])) {
				$v_user_id_del_arr=$_POST['select2'];
				for ($i=0;$i<count($v_user_id_del_arr);$i++) {
					$v_user_id=EscapeData($v_user_id_del_arr[$i]);
					$cs=new CategoryUserSecurity;
					$cs->SetParameters($v_category_id,$v_user_id);
					$cs->Delete();
				}
			}
		}
	}

	/* PROCESS THE ADVANCED STUFF */
	if (ISSET($_GET['subtask']) && ISSET($_GET['role_id']) && ISSET($_GET['category_id'])) {
		$v_category_id=$_GET['category_id'];
		$v_role_id=$_GET['role_id'];
		$crs=new CategoryRoleSecurity;
		$crs->SetParameters($v_category_id,$v_role_id);
		if (ISSET($_GET['browse'])) {
			$result=$crs->AddAdvancedSecurity("browse",$_GET['browse']);
		}
		elseif (ISSET($_GET['upload'])) {
			$result=$crs->AddAdvancedSecurity("upload",$_GET['upload']);
		}
		elseif (ISSET($_GET['delete_files'])) {
			$result=$crs->AddAdvancedSecurity("delete_files",$_GET['delete_files']);
		}
	}

	/* QUERYSTRING PROCESSING */
	if (ISSET($_GET['category_id'])) {
		$v_category_id=$_GET['category_id'];
	}
	else {
		$v_category_id=null;
	}

	/* SHOW THE FORM FOR ROLE ACCESS */
	$s=CategoryRoleSecurity($v_category_id);

	/* SHOW THE FORM FOR INDIVIDUAL ACCESS */
	//$s.=CategoryUserSecurity($v_category_id);

	return $s;
}
?>