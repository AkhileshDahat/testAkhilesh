<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once ($dr."modules/signup/functions/forms/signup_form.php");

function LoadModule() {

	$c="";

	if (ISSET($_GET['task'])) {
		$file_inc=$GLOBALS['dr']."modules/signup/modules/".$_GET['task'].".php";
		if (file_exists($file_inc)) {
			require_once($file_inc);
			if (function_exists("LoadTask")) {
				$c.=LoadTask();
			}
		}
		else {
			$c.=SignupForm();
		}
	}
	else {
		$c.=SignupForm();
	}
	return $c;
}
?>