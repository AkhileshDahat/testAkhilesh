<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/scheduling/functions/browse/browse_subject_detail.php";

function Browse() {
	return BrowseSubjectDetail();
}

?>