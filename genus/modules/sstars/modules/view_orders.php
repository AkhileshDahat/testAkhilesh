<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/sstars/functions/browse/browse_orders.php";

function LoadTask() {
	return BrowseOrders();
}
?>