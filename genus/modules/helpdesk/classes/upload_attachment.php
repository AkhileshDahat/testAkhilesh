<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

//require_once $GLOBALS['dr']."include/functions/db/get_sequence_currval.php";
require_once $GLOBALS['dr']."include/functions/filesystem/size_int.php";
//require_once $GLOBALS['dr']."modules/documents/classes/ticket_id.php";
//require_once $GLOBALS['dr']."modules/documents/classes/document_id.php";
require_once $GLOBALS['dr']."modules/helpdesk/functions/misc/ticket_history.php";
//require_once $GLOBALS['dr']."modules/documents/classes/category_role_security.php";
require_once $GLOBALS['dr']."include/functions/db/row_exists.php";

class UploadAttachment {

	function __construct() {
		$this->errors="";
	}

	public function SetParameters($ticket_id,$filename,$filetype,$filesize,$attachment) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (EMPTY($filename)) { $this->Errors("Invalid file [1]"); return False; }
		if (EMPTY($filetype)) { $this->Errors("Invalid file [2]"); return False; }
		if (EMPTY($filesize)) { $this->Errors("Invalid file [3]"); return False; }
		if (EMPTY($attachment)) { $this->Errors("Invalid file [4]"); return False; }
		if (EMPTY($ticket_id) || !IS_NUMERIC($ticket_id)) { $this->Errors("Invalid ticket [5]"); echo "error"; return False; }

		/* STORE VARIABLES */
		$this->ticket_id=$ticket_id;
		$this->filename=$filename;
		$this->filetype=$filetype;
		$this->filesize=$filesize;
		$this->attachment=$attachment;

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function UploadFile() {
		/* CHECK THE PARAMETERS */
		if (!$this->parameter_check) { $this->Errors("Parameter check failed");  return False; }

		$result=$this->SaveToDatabase();
		if ($result) {
			return True;
		}
		else {
			return False;
		}
	}

	/* SAVE TO DATABASE */
	private function SaveToDatabase() {

		$db=$GLOBALS['db'];

		/* CHECK WHICH FUNCTION TO USE TO ESCAPE BINARY DATA */
		if ($GLOBALS['database_type']=="postgres") {
			$escape_binary_function="pg_escape_bytea";
		}
		else {
			$escape_binary_function="mysql_escape_string";
		}

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."helpdesk_ticket_attachments
				(ticket_id,filename,filesize,filetype,attachment)
				VALUES (
				'".$this->ticket_id."',
				'".$this->filename."',
				'".$this->filesize."',
				'".$this->filetype."',
				'".$escape_binary_function($this->attachment)."'
				)";
		//echo $sql;
		$db->query($sql);
		if ($db->AffectedRows() > 0) {
			TicketHistory($this->ticket_id,"Attachment ".$this->filename." added");
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