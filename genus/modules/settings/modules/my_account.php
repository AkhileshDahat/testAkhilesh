<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/hrms/functions/forms/add_user_form.php";
require_once $GLOBALS['dr']."modules/hrms/classes/user_id.php";

function LoadTask() {

	$ui=$GLOBALS['ui'];
	$db=$GLOBALS['db'];

	$c="";

	/* FORM PROCESSING */
	if (ISSET($_POST['signup'])) {

		$db->Begin(); /* START THE TRANSACTION */

		$userid=new UserID; /* NEW OBJECT FOR USERS */
		$userid->SetParameters($_SESSION['user_id']);

		$result_edit=$userid->EditUser($_POST['title_id'],$_POST['full_name'],$_POST['login'],$_POST['password'],$_POST['password_repeat'],$_POST['timezone'],$_POST['country_id'],$_POST['language']);
		if (!$result_edit) {
			$db->Rollback(); /* FAILED FOR SOME REASON */
			$c.="Failed";
			$c.=$userid->ShowErrors();
		}
		else {
			$db->Commit(); /* SAVE CHANGES */
			$c.="Success...<a href='index.php?module=settings&task=my_account'>continue</a>";
		}
	}

	else {
		/* CALL THE FUNCTION TO ADD USERS PASSING IN PARAMETERS ONLY IF NOT EDITING OR CHANGES ARE ONLY DISPLAYED ON A REFRESH */
		$c.=AddUserForm($ui->GetInfo("title_id"),$ui->GetInfo("full_name"),$ui->GetInfo("login"),$ui->GetInfo("timezone"),$ui->GetInfo("country_id"),$ui->GetInfo("language"),"?module=settings&task=my_account","edit");
	}

	return $c;
}
?>