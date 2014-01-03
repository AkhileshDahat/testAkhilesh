<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/sstars/functions/browse/order_id_summary.php";

function LoadTask() {

	$c="";
	return OrderIDSummary($_GET['order_id']);
}
?>