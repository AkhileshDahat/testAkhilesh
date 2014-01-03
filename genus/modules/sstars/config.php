<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* LANGUAGE */
if (file_exists($GLOBALS['dr']."modules/sstars/language/".$GLOBALS['ui']->GetInfo("language").".php")) {
	$language_file=$GLOBALS['dr']."modules/sstars/language/".$GLOBALS['ui']->GetInfo("language").".php";
	require_once $language_file;
}
else {
	require_once $GLOBALS['dr']."modules/sstars/language/en.php";
}

/* MENU */
GLOBAL $mainmenu;
$mainmenu = array("Home",
									"Batch",
									"Show Batches",
									"Order",
									"View Orders",
									"My Orders",
									"Categories",
									"Sub Categories",
									"ACL"
									);

//require_once($dr."modules/sstars/language/en.php");

//require_once($dr."modules/sstars/classes/document_setup.php");
//$ds=new sstarsetup();
?>