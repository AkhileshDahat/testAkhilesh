<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."classes/design/html_head.php";
require_once $GLOBALS['dr']."classes/smtp/smtp.php";

require_once $GLOBALS['dr']."modules/hrms/classes/user_id.php";
require_once $GLOBALS['dr']."modules/signup/functions/forms/recover_password.php";

function LoadTask() {

	$s="";

	/* FORM PROCESSING */
	if (ISSET($_POST['FormSubmit'])) {
		$o_ui_recover=new UserID;
		$v_user_id=$o_ui_recover->GetUserID($_POST['email_address']);
		$o_ui_recover->SetParameters($v_user_id);

		if (!$o_ui_recover->PasswordRecoveryExists()) {

			/* A SECRET STRING FOR PASSWORD RECOVERY */
			$v_secret_string=MD5($v_user_id.microtime().rand(1,10000));
			$v_body="A request for your password was submitted. If you did not make the request, please ignore this. If you require your password to be reset, please click here <a href='".$GLOBALS['wb']."index.php?module=signup&task=reset_password&key=".$v_secret_string."'>".$GLOBALS['wb']."index.php?module=signup&task=reset_password&key=".$v_secret_string."</a>";

			$o_ui_recover->AddPasswordRecovery($v_user_id,$v_secret_string);

			/* USE THE MAIN TEMPLATE FOR THIS */

			$obj_design=new HTMLHead;
			$obj_design->IncludeFile("css");
			$html_content=$obj_design->DrawHead();
			$html_content.=$obj_design->DrawBody($v_body);
			$html_content.=$obj_design->DrawFoot();

			$v_email=SendEmail($GLOBALS['email_recover_password_from'],$_POST['email_address'],"Password Recovery Request",$html_content);
			if ($v_email) {
				$s.="Email sent successfully";
			}
			else {
				$s.="Email request failed.";
			}
		}
		else {
			$s.="You already have an outstanding request. If you have not received the email you need to wait for 6 hours before requesting again";
		}
	}

	/* SHOW THE FORM */
	$s.=RecoverPassword();

	return $s;
}
?>