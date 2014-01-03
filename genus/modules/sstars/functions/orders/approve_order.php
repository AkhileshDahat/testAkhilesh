<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

//require_once $dr."modules/sstars/functions/order/log_history.php";
require_once $dr."include/functions/db/count_rows.php";
require_once $dr."include/functions/db/get_col_value.php";
require_once $dr."modules/sstars/functions/orders/change_status.php";

function ApproveOrder($order_id) {
	$db=$GLOBALS['db'];

	$sql="UPDATE ".$GLOBALS['database_prefix']."order_approval
				SET status = 'approved'
				WHERE order_id = '".$order_id."'
				AND user_id = '".$GLOBALS['user_id']."'
				";
	//echo $sql."<br>";
	$db->Query($sql);
	if ($db->AffectedRows() > 0) {
		//LogBatchHistory($order_id,$order_id." confirmed",$GLOBALS['user_id']);
		if (CountRows("order_approval","order_id","*","AND status = 'pending'")==0) {
			$status_id=GetColumnValue("status_id","order_status_master","is_ordered","y","");
			ChangeStatus($order_id,$status_id);
		}
		return True;
	}
	else {
		return False;
	}
}
?>