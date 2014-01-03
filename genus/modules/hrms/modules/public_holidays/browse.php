<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/hrms/functions/browse/browse_public_holidays.php";

function Browse() {
	return BrowsePublicHolidays();
}

?>