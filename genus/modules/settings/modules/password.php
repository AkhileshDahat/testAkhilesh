<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/settings/functions/forms/password.php";


function LoadTask() {

	$c="";
	
	$db=$GLOBALS['db'];
	if (ISSET($_POST['submit'])) {

		require_once $GLOBALS['dr']."modules/core/classes/core_user_master.php";
		$obj=new CoreUserMaster;
		$obj->SetParameters($_SESSION['user_id']);

		$result=$obj->ChangePassword($_POST['password'],$_POST['r_password']);
		if ($result) {
			$GLOBALS['errors']->SetAlert("Successfully set password");
		}
		else {
			$GLOBALS['errors']->SetError($obj->ShowErrors());
		}
	}


	$c .= PasswordForm();

	return $c;
}
?>