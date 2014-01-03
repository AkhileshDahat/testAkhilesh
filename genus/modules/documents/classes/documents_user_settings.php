<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class DocumentsUserSettings {

	public function SetParameters($user_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* STORE IN CLASS GLOBAL VARIABLE */
		$this->user_id=$user_id;

		/* ENSURE IT'S A NUMBER */
		if (!IS_NUMERIC($this->user_id)) { $this->Errors("Please login"); return False; }

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		/* WE DO NOT CALL THE INFO FUNCTION BECAUSE OF CHANGES MADE. IT IS CALLED SEPARATELY IN THE DOCUMENTS.PHP FILE */
		//$this->Info();

		return True;
	}

	public function Info() {

		$db=$GLOBALS['db'];

		$sql="SELECT col_owner, col_rating, col_size, show_rad_upload, total_cut_documents
					FROM ".$GLOBALS['database_prefix']."document_user_settings
					WHERE user_id = ".$this->user_id."
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$this->col_owner=$row["col_owner"];
				$this->col_rating=$row["col_rating"];
				$this->col_size=$row["col_size"];
				$this->show_rad_upload=$row["show_rad_upload"];
				$this->total_cut_documents=$row["total_cut_documents"];
			}
		}
		else {
			/* ADD USER IF THEY DO NOT EXIST */
			$this->AddUser();
			/* CALL THE FUNCTION AGAIN ONCE WE'VE DONE THIS */
			$this->Info();
		}
	}

	public function ChangeShowRadUpload($val) {

		$db=$GLOBALS['db'];

		if ($val!="y") { $val="n"; }

		$sql="UPDATE ".$GLOBALS['database_prefix']."document_user_settings
					SET show_rad_upload = '".$val."'
					WHERE user_id = '".$this->user_id."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->Errors("Failed to change your settings");
			return False;
		}
	}

	/* THIS ADDS THE USER IF THEY DO NOT HAVE A RECORD IN THE SETTINGS TABLE */

	private function AddUser() {

		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."document_user_settings
					(user_id,workspace_id,teamspace_id) VALUES (".$this->user_id.",".$GLOBALS['workspace_id'].",".$GLOBALS['teamspace_id'].")
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			return False;
		}
	}

	public function GetInfo($v) {
		return $this->$v;
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}
}
?>