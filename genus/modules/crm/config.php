<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* LANGUAGE */
if (file_exists($GLOBALS['dr']."modules/crm/language/".$GLOBALS['ui']->GetInfo("language").".php")) {
	$language_file=$GLOBALS['dr']."modules/crm/language/".$GLOBALS['ui']->GetInfo("language").".php";
	require_once $language_file;
}
else {
	require_once $GLOBALS['dr']."modules/crm/language/en.php";
}

/* MENU */
GLOBAL $mainmenu;
$mainmenu = array("Home",
									"Accounts",
									"Add Accounts",
									"Contacts",
									"Add Contact",
									"ACL"
									);

?>