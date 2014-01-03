<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/hrms/classes/user_id.php";
require_once $GLOBALS['dr']."modules/signup/functions/recover/key_exists.php";

function LoadTask() {

	$s="";

	if (ISSET($_GET['key'])) {

		$v_user_id=KeyExists($_GET['key']);

		if ($v_user_id) {

			/* TEMP PASSWORD */
			$v_password=SUBSTR(MD5($v_user_id.microtime()),0,5);

			$o_ui=new UserID();
			$o_ui->SetParameters($v_user_id);
			$o_ui->ChangePassword($v_password,$v_password);
			$s.="<h1>Password Recovery</h1>Your password has been reset. It has been changed to '".$v_password."'. Please login now and change it!<h3>MVH Team</h3>";
		}
	}

	return $s;
}
?>