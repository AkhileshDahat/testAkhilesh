<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class DocumentID {

	function __construct() {
		$this->debug=False;
	}
	public function SetParameters($document_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($document_id)) { $this->Errors("Invalid announcement"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->document_id=$document_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info();

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT sm.workspace_id,sm.teamspace_id,sm.filename,sm.filetype,sm.filesize,sm.user_id,sm.date_added,um.full_name
					FROM ".$GLOBALS['database_prefix']."simpledoc_files sm,".$GLOBALS['database_prefix']."core_user_master um
					WHERE sm.document_id = ".$this->document_id."
					AND sm.workspace_id = ".$GLOBALS['workspace_id']."
					AND sm.teamspace_id ".$GLOBALS['teamspace_sql']."
					AND sm.user_id = um.user_id
					";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				/* HERE WE CALL THE FIELDS AND SET THEM INTO DYNAMIC VARIABLES */
				$arr_cols=$this->db->GetColumns($result);
				for ($i=1;$i<count($arr_cols);$i++) {
					$col_name=$arr_cols[$i];
					$this->$col_name=$row[$col_name];
				}
			}
		}
	}
	
	/* THIS IS USED IN DOWNLOAD PAGES - BECAUSE WE RETURN THE DOCUMENT WHICH CAN BE HUGE */
	public function DocumentAttachment() {

		if ($this->parameter_check) {

			$db=$GLOBALS['db'];

			$sql="SELECT attachment
						FROM ".$GLOBALS['database_prefix']."simpledoc_files
						WHERE document_id = '".$this->document_id."'
						";
			//echo $sql."<br>";
			$result = $db->Query($sql);
			if ($db->NumRows($result) > 0) {
				while($row = $db->FetchArray($result)) {
					if ($GLOBALS['database_type'] == "postgres") {
						return pg_unescape_bytea($row["attachment"]);
					}
					else {
						return $row["attachment"];
					}
				}
			}
		}
	}

	public function GetInfo($v) {
		if (ISSET($this->$v)) {
			return $this->$v;
		}
		else {
			return "";
		}
	}

	public function Add() {

		/* CHECKS */
		$this->Debug("Adding file now");
		//if(EMPTY($this->title)) { $this->Errors("Please enter a title!"); return False; }
		//if(EMPTY($this->message)) { $this->Errors("Please enter a message!"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."simpledoc_files
					(workspace_id,teamspace_id,filename,filetype,filesize,attachment,user_id,date_added)
					VALUES (
					".$GLOBALS['workspace_id'].",
					".$GLOBALS['teamspace_id'].",
					'".$this->filename."',
					'".$this->filetype."',
					'".$this->filesize."',
					'".mysql_escape_string($this->attachment)."',
					".$_SESSION['user_id'].",
					now()
					)";
		//echo $sql;
		$result=$db->query($sql);
		//$result=True; // enable for debugging
		if ($result) {
			$this->document_id=$db->LastInsertID();
			return True;
		}
		else {
			return False;
		}
	}
	
	public function Delete() {

		if (!$this->parameter_check) { $this->Errors("Invalid document to delete"); return False; }
		
		/* CHECKS */
		$this->Debug("Deleting now");		

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="DELETE FROM ".$GLOBALS['database_prefix']."simpledoc_files
					WHERE document_id = ".$this->document_id."
					";					
		//echo $sql;
		$result=$db->query($sql);
		//$result=True; // enable for debugging
		if ($result) {			
			return True;
		}
		else {
			return False;
		}
	}
	
	public function GetUploadedFile() {
					
		
		if (ISSET($_FILES['attachment'])) {			
			
			$file = $_FILES['attachment'];
			
			$this->filename=EscapeData($file['name']);
			//echo $this->filetype;
			$this->filetype=EscapeData($file['type']);
			$this->filesize=EscapeData($file['size']);
			/* READ FILE FROM TEMP LOCATION */
			$handle = fopen($file['tmp_name'],"rb");
			$this->attachment=fread($handle, filesize ($file['tmp_name']));
		}
		else {
			echo "error";
			$this->Errors("Upload file missing");
			return false;			
		}	
		
	}

	public function SetVariable($variable,$variable_val) {
		$this->$variable=EscapeData($variable_val);
	}

	public function Debug($desc) {
		if ($this->debug==True) {
			echo $desc."<br>\n";
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