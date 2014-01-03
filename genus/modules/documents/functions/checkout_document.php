<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."modules/documents/functions/log_history.php";
require_once $dr."include/functions/db/get_col_value.php";

function CheckoutDocument($document_id,$checked_out_user) {
	$db=$GLOBALS['db'];
	/*
		DESCRIPTION MUST ALWAYS COME FIRST BEFORE WE DELETE THE RECORDS
	*/
	$description=GetColumnValue("filename","document_files","document_id",$document_id,""). " - checked out";

	$sql="UPDATE ".$GLOBALS['database_prefix']."document_files
				SET checked_out = 'y',
				checked_out_user = '".$checked_out_user."',
				checked_out_date = sysdate()
				WHERE document_id = '".$document_id."'
				";
	$db->Query($sql);
	if ($db->AffectedRows() > 0) {
		LogDocumentFileHistory($document_id,$GLOBALS['user_id'],$description);
		return True;
	}
	else {
		return False;
	}
}
?>