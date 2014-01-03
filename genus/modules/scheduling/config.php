<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* LANGUAGE */
if (file_exists($GLOBALS['dr']."modules/helpdesk/language/".$GLOBALS['ui']->GetInfo("language").".php")) {
	$language_file=$GLOBALS['dr']."modules/helpdesk/language/".$GLOBALS['ui']->GetInfo("language").".php";
	require_once $language_file;
}
else {
	require_once $GLOBALS['dr']."modules/helpdesk/language/en.php";
}

/* HELPDESK SETTING CLASS */
//require_once($dr."modules/helpdesk/classes/helpdesk_master.php");
//$hm=new HelpdeskMaster();
GLOBAL $mainmenu;

$mainmenu = array("Home",
							"Reserve Slots",
							"Schedule",
							"Lecturers Schedule",
							"Students Schedule",
							"Subjects",
							"Subject Detail",
							"Global Reserve Slots",
							"ACL"
							);

/* THE DAYS IN THE WEEK THAT ARE WORKING DAYS */
global $config_working_week_days;
$config_working_week_days=array("mon","tue","wed","thu","fri");
/* START HOUR OF THE DAY IN MINUTES SINCE MIDNIGHT */
global $config_start_time;
$config_start_time=510;
/* END HOUR OF THE DAY IN MINUTES SINCE MIDNIGHT */
global $config_end_time;
$config_end_time=1050;
/* INTERVAL OF TIMESLOTS IN MINUTES */
global $config_interval;
$config_interval=60;
?>