<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."modules/documents/functions/log_history.php";
require_once $dr."include/functions/db/get_col_value.php";

function UnlockDocument($document_id,$locked_user_id) {
	$db=$GLOBALS['db'];
	/*
		DESCRIPTION MUST ALWAYS COME FIRST BEFORE WE DELETE THE RECORDS
	*/
	$description=GetColumnValue("filename","document_files","document_id",$document_id,""). " - unlocked";

	$sql="UPDATE ".$GLOBALS['database_prefix']."document_files
				SET locked = 'n',
				locked_user_id = '',
				locked_date = ''
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