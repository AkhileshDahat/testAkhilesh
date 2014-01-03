<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* LANGUAGE */
if (ISSET($GLOBALS['ui']) && file_exists($GLOBALS['dr']."modules/documents/language/".$GLOBALS['ui']->GetInfo("language").".php")) {
	$language_file=$GLOBALS['dr']."modules/documents/language/".$GLOBALS['ui']->GetInfo("language").".php";
	require_once $language_file;
}
else {
	require_once $GLOBALS['dr']."modules/documents/language/en.php";
}

/* ENABLE THIS IF YOU HAVE PURCHASE RADUPLOADPLUS - NO FILE UPLOAD LIMIT */
//$raduploadplus=False;
$raduploadplus=True;
$raduploaddir="raduploadplus";
?>