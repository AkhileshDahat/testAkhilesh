<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

//require_once $GLOBALS['dr']."classes/design/html_head.php";
//require_once $GLOBALS['dr']."classes/smtp/smtp.php";

//require_once $GLOBALS['dr']."modules/hrms/classes/user_id.php";
//require_once $GLOBALS['dr']."modules/signup/functions/forms/recover_password.php";

function LoadTask() {

	$db=$GLOBALS['db'];

	$s="";

	if (ISSET($_GET['activation_code']) && ctype_alnum($_GET['activation_code'])) {
		$sql="UPDATE ".$GLOBALS['database_prefix']."core_user_master
					SET activated = 'y'
					WHERE activation_code = '".$_GET['activation_code']."'
					";
		//echo $sql;
		$result=$db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			$s.=CurveBox("Success, your account is activated");
		}
		else {
			$s.=CurveBox("Invalid activation code [1], or you are already activated.");
		}
	}
	else {
		$s.=CurveBox("Invalid activation code [2]");
	}

	return $s;
}
?>