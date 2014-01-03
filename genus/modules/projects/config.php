<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* LANGUAGE */
if (file_exists($GLOBALS['dr']."modules/projects/language/".$GLOBALS['ui']->GetInfo("language").".php")) {
	$language_file=$GLOBALS['dr']."modules/projects/language/".$GLOBALS['ui']->GetInfo("language").".php";
	require_once $language_file;
}
else {
	require_once $GLOBALS['dr']."modules/projects/language/en.php";
}

/* MENU */
GLOBAL $mainmenu;
$mainmenu = array(_PROJECTS_MENU_HOME_,
									_PROJECTS_MENU_ADD_,
									_PROJECTS_MENU_VIEW_,
									_PROJECTS_MENU_CATEGORIES_,
									_PROJECTS_MENU_STATUS_,
									_PROJECTS_MENU_ACL_
									);

?>