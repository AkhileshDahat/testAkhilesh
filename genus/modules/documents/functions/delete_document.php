<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."modules/documents/functions/log_history.php";
require_once $dr."include/functions/db/get_col_value.php";

function DeleteDocument($document_id) {
	$db=$GLOBALS['db'];
	/*
		DESCRIPTION MUST ALWAYS COME FIRST BEFORE WE DELETE THE RECORDS
	*/
	$filename=EscapeData(GetColumnValue("filename","document_files","document_id",$document_id,""));
	$description=$filename." deleted";

	/* MUST LOG BEFORE WE DELETE TO AVOID FOREIGN KEY CONSTRAINT */
	LogDocumentHistory($GLOBALS['user_id'],$description);

	$sql="DELETE FROM ".$GLOBALS['database_prefix']."document_files
				WHERE filename = '".$filename."'
				";
	$db->Query($sql);
	if ($db->AffectedRows() > 0) {
		return True;
	}
	else {
		return False;
	}
}
?>