<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

//require_once $dr."modules/sstars/functions/order/log_history.php";
require_once $dr."include/functions/db/get_col_value.php";

function ConfirmOrder($order_id) {
	$db=$GLOBALS['db'];
	$status_id=GetColumnValue("status_id","order_status_master","is_pending_approval","y","");
	$sql="UPDATE ".$GLOBALS['database_prefix']."order_master
				SET status_id = '".$status_id."'
				WHERE order_id = '".$order_id."'
				";
	//echo $sql."<br>";
	$db->Query($sql);
	if ($db->AffectedRows() > 0) {
		//LogBatchHistory($order_id,$order_id." confirmed",$GLOBALS['user_id']);
		return True;
	}
	else {
		return False;
	}
}
?>