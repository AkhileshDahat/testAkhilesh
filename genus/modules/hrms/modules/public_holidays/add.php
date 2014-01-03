<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/hrms/classes/public_holiday.php";
require_once $GLOBALS['dr']."modules/hrms/functions/forms/add_public_holiday.php";

function Add() {

	$c="";

	/* SHOW THE FORM */
	$c.=AddPublicHoliday();

	return $c;
}
?>