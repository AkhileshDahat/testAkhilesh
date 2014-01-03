<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/survey/functions/browse/browse_surveys.php";

function LoadTask() {
	$c="";

	/* INCLUDE THE BROWSE FUNCTION */
	$c.=BrowseSurveys();

	return $c;
}
?>