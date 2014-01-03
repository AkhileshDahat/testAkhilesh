<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."modules/sstars/functions/inventory/log_history.php";
require_once $dr."include/functions/db/get_col_value.php";

function DeleteItem($batch_id,$item_id) {
	$db=$GLOBALS['db'];

	$sql="DELETE FROM ".$GLOBALS['database_prefix']."batch_items
				WHERE item_id = '".$item_id."'
				";
	$db->Query($sql);
	if ($db->AffectedRows() > 0) {
		LogBatchHistory($batch_id,$item_id." deleted",$GLOBALS['user_id']);
		return True;
	}
	else {
		return False;
	}
}
?>