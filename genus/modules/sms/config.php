<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* LANGUAGE */
if (file_exists($GLOBALS['dr']."modules/sms/language/".$GLOBALS['ui']->GetInfo("language").".php")) {
	$language_file=$GLOBALS['dr']."modules/sms/language/".$GLOBALS['ui']->GetInfo("language").".php";
	require_once $language_file;
}
else {
	require_once $GLOBALS['dr']."modules/sms/language/en.php";
}

/* MENU */
GLOBAL $mainmenu;
$mainmenu = array(_SMS_MENU_HOME_,
									_SMS_MENU_SEND_,
									_SMS_MENU_SENT_,
									_SMS_MENU_ACL_
									);

?>