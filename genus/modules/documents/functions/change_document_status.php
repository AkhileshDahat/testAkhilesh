<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."modules/documents/functions/log_history.php";
require_once $dr."include/functions/db/get_col_value.php";

function ChangeDocumentStatus($document_id,$status_id) {
	$db=$GLOBALS['db'];
	/*
		DESCRIPTION MUST ALWAYS COME FIRST BEFORE WE DELETE THE RECORDS
	*/
	$new_status=GetColumnValue("status_name","document_status_master","status_id",$status_id,"");
	$description="Status chanegd to ".$new_status;

	$sql="UPDATE ".$GLOBALS['database_prefix']."document_files
				SET status_id = '".$status_id."'
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