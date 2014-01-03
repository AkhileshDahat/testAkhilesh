<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."modules/documents/functions/log_history.php";
require_once $dr."include/functions/db/get_col_value.php";

class DeleteDocumentVersion {

	/* GET THE DEFAULT PARAMETERS AND DO SOME CHECKING */
	public function SetParameters($document_id) {

		/* DATA CHECKING */
		if (!IS_NUMERIC($document_id)) { $this->Errors("Invalid document. Non numeric value!"); return False; }
		if (EMPTY($_SESSION['user_id'])) { $this->Errors("Invalid User. Please login"); return False; }

		/* SET THE VARIABLE IN GLOBAL SCOPE */
		$this->document_id=$document_id;
	}

	public function DeleteDocumentVersion($document_id) {

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		/* DESCRIPTION MUST ALWAYS COME FIRST BEFORE WE DELETE THE RECORDS */
		$description=GetColumnValue("filename","document_files","document_id",$document_id,""). " - deleted";

		/* LOG BEFORE DELETE TO AVOID FOREIGN KEY CONSTRAINT */
		LogDocumentHistory($GLOBALS['user_id'],$description);

		/* PROCESS SQL */
		$sql="DELETE FROM ".$GLOBALS['database_prefix']."document_files
					WHERE document_id = '".$document_id."'
					";
		$db->Query($sql);
		if ($db->AffectedRows() > 0) {
			return True;
		}
		else {
			return False;
		}
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}
}
?>