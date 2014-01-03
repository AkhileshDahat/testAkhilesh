<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/leave/classes/user_settings.php";

/* LANGUAGE */
if (file_exists($GLOBALS['dr']."modules/leave/language/".$GLOBALS['ui']->GetInfo("language").".php")) {
	$language_file=$GLOBALS['dr']."modules/leave/language/".$GLOBALS['ui']->GetInfo("language").".php";
	require_once $language_file;
}
else {
	require_once $GLOBALS['dr']."modules/leave/language/en.php";
}

/* MENU */
GLOBAL $mainmenu;
$mainmenu = array("Home",
									"Dashboard",
									"Apply",
									"My Applications",
									"Balance",
									"Categories",
									"Periods",
									"Status",
									"User Balances",
									"User Balances Grid",
									"HOD Approval",
									"Global Approval",
									"Reports",
									"Reports Admin",
									"Balance Transfer",
									"Workflow",
									"History",
									"ACL"
									);


/* USER SETTINGS */
GLOBAL $obj_us;
$obj_us=new UserSettings;

/* LEAVE SETTINGS */
global $leave_total_saturdays_value;
$leave_total_saturdays_value = 0.5;

/* THIS DETERMINS HOW MANY DAYS A SUNDAY IS WORTH */

global $leave_total_sundays_value;
$leave_total_sundays_value = 0;

/* THIS WILL RECORD EXACTLY HOW THE TOTAL DAYS ARE CALCULATED FOR EACH APPLICATION */
global $enable_debugging_leave;
$enable_debugging_leave = true;

?>