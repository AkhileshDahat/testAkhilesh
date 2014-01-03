<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* LANGUAGE */
if (file_exists($GLOBALS['dr']."modules/visitors/language/".$GLOBALS['ui']->GetInfo("language").".php")) {
	$language_file=$GLOBALS['dr']."modules/visitors/language/".$GLOBALS['ui']->GetInfo("language").".php";
	require_once $language_file;
}
else {
	require_once $GLOBALS['dr']."modules/visitors/language/en.php";
}

/* MENU */
GLOBAL $mainmenu;
$mainmenu = array("Home",
									"Take Survey",
									"Add Survey",
									"Browse Surveys",
									"Reports",
									"ACL"
									);

GLOBAL $survey_options;
$survey_options=array("Completely Disagree","Disagree","Neutral","Agree","Definately Agree");
$survey_options_brief=array("Comp Disagree","Disagree","Neutral","Agree","Def Agree");
?>