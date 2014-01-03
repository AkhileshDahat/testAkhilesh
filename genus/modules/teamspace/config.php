<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* LANGUAGE */
if (file_exists($GLOBALS['dr']."modules/teamspace/language/".$GLOBALS['ui']->GetInfo("language").".php")) {
	$language_file=$GLOBALS['dr']."modules/teamspace/language/".$GLOBALS['ui']->GetInfo("language").".php";
	require_once $language_file;
}
else {
	require_once $GLOBALS['dr']."modules/teamspace/language/en.php";
}

/* MENU */
GLOBAL $mainmenu;
$mainmenu = array(_TEAMSPACE_MENU_HOME_,
									_TEAMSPACE_MENU_NEW_TEAMSPACE_,
									_TEAMSPACE_MENU_BROWSE_TEAMSPACES_,
									_TEAMSPACE_MENU_TEAMSPACE_ACLS_,
									_TEAMSPACE_MENU_ACL_
									);

?>