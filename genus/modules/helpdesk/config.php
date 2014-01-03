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
							"Create Ticket",
							"All Tickets",
							"Ticket Plugins",
							"Delegated Tickets",
							"My Tickets",
							"Dashboard",
							"Reports",
							"Categories",
							"Priorities",
							"Significance",
							"Tags",
							"Status",
							"ACL"
							);

?>