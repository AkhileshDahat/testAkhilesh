<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/db/get_sequence_currval.php";
require_once $GLOBALS['dr']."include/functions/filesystem/size_int.php";
require_once $GLOBALS['dr']."modules/documents/classes/category_id.php";
require_once $GLOBALS['dr']."modules/documents/classes/document_id.php";
require_once $GLOBALS['dr']."modules/documents/functions/misc/log_history.php";
require_once $GLOBALS['dr']."modules/documents/classes/category_role_security.php";
require_once $GLOBALS['dr']."include/functions/db/row_exists.php";

class UploadDocument {

	function __construct() {
		$this->errors="";
	}

	public function SetParameters($filename,$filetype,$filesize,$category_id,$attachment) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (EMPTY($filename)) { $this->Errors("Invalid file [1]"); return False; }
		if (EMPTY($filetype)) { $this->Errors("Invalid file [2]"); return False; }
		if (EMPTY($filesize)) { $this->Errors("Invalid file [3]"); return False; }
		if (EMPTY($attachment)) { $this->Errors("Invalid file [4]"); return False; }
		if (EMPTY($category_id) || !IS_NUMERIC($category_id)) { $this->Errors("Invalid category [5]"); echo "error"; return False; }

		$category_exists=RowExists("document_categories","category_id",$category_id,"AND workspace_id = '".$GLOBALS['workspace_id']."' AND teamspace_id = '".$GLOBALS['teamspace_sql']."'");
		/* STORE VARIABLES */
		$this->filename=$filename;
		$this->filetype=$filetype;
		$this->filesize=$filesize;
		$this->category_id=$category_id;
		$this->attachment=$attachment;

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function UploadFile() {

		/* CHECK THE PARAMETERS */
		if (!$this->parameter_check) { $this->Errors("Parameter check failed");  return False; }
		/* SEE IF THERE IS ALREADY A DOCUMENT BY THIS NAME */
		$this->document_id=GetColumnValue("document_id","document_files","filename",$this->filename,"AND category_id='".$this->category_id."' ORDER BY version_number DESC LIMIT 1");
		if (!EMPTY($this->document_id)) {
			$di=new DocumentID;
			$result=$di->SetParameters($this->document_id);
			if (!$result) {
				//echo "Failed";
				//echo $di->ShowErrors();
				$this->Errors("Unable to open document");
				return False;
			}
		}

		/*
			ALLOW IF:
			1. THIS IS THE FIRST VERSION OF THE DOCUMENT - NO DOCUMENT ID FROM ABOVE
			2. THE DOCUMENT EXISTS AND IT HAS BEEN CHECKED OUT AND THIS IS THE USER WHO CHECKED IT OUT
			3. THE FILE IS NOT LOCKED
		*/
		$upload_file=True;
		if (EMPTY($this->document_id)) {
			$this->version_number=1;
		}
		else {
			/* GET THE NEXT VERSION NUMBER */
			$this->version_number=($di->GetColVal("version_number")+1);

			/* ALL DOCUMENTS MUST BE CHECKED OUT */
			if ($di->GetColVal("checked_out") == "n") { $this->Errors("Not checked out"); return False;  }
			/* THE FILE MUST NOT BE LOCKED */
			if ($di->GetColVal("locked") == "y") {  $this->Errors("File locked"); return False; }
			/* THIS MUST BE THE PERSON WHO CHECKED OUT THE DOCUMENT */
			if ($di->GetColVal("user_id_checked_out") != $_SESSION['user_id']) { $this->Errors("Not your file"); return False; }
		}
		$result=$this->SaveToDatabase();
		if ($result) {
			//echo "ok";
			return True;
		}
		else {
			//echo "err";
			return False;
		}
	}

	/* SAVE TO DATABASE */
	private function SaveToDatabase() {

		$db=$GLOBALS['db'];

		$user_id=$_SESSION['user_id'];
		//echo $_SESSION['user_id']." - userid<br>";

		/* CHECK IF THE CATEGORY REQUIRES APPROVAL */
		$ci=new CategoryID;
		$ci->SetParameters($this->category_id);
		if ($ci->GetInfo("requires_approval")=="y") { $sql_status_col="is_pending"; } else { $sql_status_col="is_current"; }

		/* GET THE STATUS ID */
		$status_id=GetColumnValue("status_id","document_status_master",$sql_status_col,"y","");
		if ($status_id < 1) { $this->Errors("No status setup for this system"); return False; }

		/* CHECK WHICH FUNCTION TO USE TO ESCAPE BINARY DATA */
		if ($GLOBALS['database_type']=="postgres") {
			$escape_binary_function="pg_escape_bytea";
		}
		else {
			$escape_binary_function="mysql_escape_string";
		}


		/* CREATE A DOWNLOAD KEY */
		//$anonymous_download_key=MD5($_SESSION['user_id'].$this->filename.$this->filetype.$this->filesize.microtime());

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."document_files
				(category_id,status_id,date_start_publishing,user_id,date_added,filename,filetype,filesize,attachment,version_number,latest_version,anonymous_download_key)
				VALUES (
				'".$this->category_id."',
				'".$status_id."',
				now(),
				'".$_SESSION['user_id']."',
				now(),
				'".$this->filename."',
				'".$this->filetype."',
				'".$this->filesize."',
				'".mysql_escape_string($this->attachment)."',
				'".$this->version_number."',
				'y',
				'".$anonymous_download_key."'
				)";
		//
		//echo $sql;

		$db->query($sql);
		//return "";
		/* GRAB THE LAST INSERTED ID */
		$this->document_id=$db->LastInsertId("s_document_files_document_id");
		/* UPDATE ALL OTHER FILE VERSIONS */
		$this->UpdateLatestVersion();
		return True;
	}

	/* HERE WE ADD APPROVERS TO THE DOCUMENT IN THE CATEGORY */
	function AddApprovers($category_id,$document_id) {

		$db=$GLOBALS['db'];

		$sql="SELECT user_id
					FROM ".$GLOBALS['database_prefix']."document_categories_approvers
					WHERE category_id = ".$this->category_id."
					";
		$result = $db->Query($sql);
		//echo $sql."<br>";
		//echo $db->NumRows($result)."<br>";
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$sql="INSERT INTO ".$GLOBALS['database_prefix']."document_file_approval
							(document_id,user_id)
							VALUES (
							".$document_id.",
							".$row['user_id']."
							)";
				//echo $sql."<br>";
				$result_ins = $db->Query($sql);
			}
		}
		return True;
	}

	private function UpdateLatestVersion() {
		$db=$GLOBALS['db'];

		$sql="UPDATE ".$GLOBALS['database_prefix']."document_files
					SET latest_version = 'n'
					WHERE category_id = ".$this->category_id."
					AND filename = '".$this->filename."'
					AND document_id <> '".$this->document_id."'
					";
		$result = $db->Query($sql);
		//echo $sql."<br>";
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}
}
?>