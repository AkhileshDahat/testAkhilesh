<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/helpdesk/functions/browse/browse_significance.php";

function Browse() {
	return BrowseSignificance();
}

?>