<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* LANGUAGE */
if (file_exists($GLOBALS['dr']."modules/facilities/language/".$GLOBALS['ui']->GetInfo("language").".php")) {
	$language_file=$GLOBALS['dr']."modules/facilities/language/".$GLOBALS['ui']->GetInfo("language").".php";
	require_once $language_file;
}
else {
	require_once $GLOBALS['dr']."modules/facilities/language/en.php";
}

/* MENU */
GLOBAL $mainmenu;
$mainmenu = array(_FACILITIES_MENU_HOME_,
									_FACILITIES_MENU_FACILITIES_,
									_FACILITIES_MENU_BOOKINGS_,
									_FACILITIES_MENU_MY_BOOKINGS_,
									_FACILITIES_MENU_AVAILABILITY_,
									_FACILITIES_MENU_ACL_
									);

?>