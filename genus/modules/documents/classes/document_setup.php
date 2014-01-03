<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class DocumentSetup {

	function __construct() {
		$this->errors="";

		$db=$GLOBALS['db'];

		$sql="SELECT show_document_rating,enable_document_encryption,enable_anonymous_document_links,theme
				FROM ".$GLOBALS['database_prefix']."document_setup
				WHERE workspace_id = ".$GLOBALS['workspace_id']."
				AND teamspace_id ".$GLOBALS['teamspace_sql']."
				";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$this->show_document_rating=$row["show_document_rating"];
				$this->enable_document_encryption=$row["enable_document_encryption"];
				$this->enable_anonymous_document_links=$row["enable_anonymous_document_links"];
				$this->theme=$row["theme"];

			}
		}
		else {
			$sql="INSERT INTO ".$GLOBALS['database_prefix']."document_setup (workspace_id,teamspace_id) VALUES (".$GLOBALS['workspace_id'].",".$GLOBALS['teamspace_id'].")";
			//echo $sql."<br>";
			$result = $db->Query($sql);

			$arr_status=array("current","pending","archived","deleted","rejected");
			foreach ($arr_status as $a) {
				$sql="INSERT INTO ".$GLOBALS['database_prefix']."document_status_master (status_name,is_".$a.") VALUES ('".$a."','y')";
				$result = $db->Query($sql);
			}

			/* CALL THE CONSTRUCTOR AGAIN */
			$this->__construct();
		}
	}

	function GetInfo($v) {	return $this->$v; }

	public function AddSettings() {
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."document_setup
					(workspace_id,teamspace_id)
					VALUES
					(
					'".$GLOBALS['workspace_id']."',
					".$GLOBALS['teamspace_id']."
					)";
		//echo $sql."<br>";
		$result = $db->Query($sql);
	}

	public function Edit($show_document_rating,$enable_document_encryption,$enable_anonymous_document_links,$theme) {
		$db=$GLOBALS['db'];

		$sql="UPDATE ".$GLOBALS['database_prefix']."document_setup
					SET  show_document_rating = '".$show_document_rating."',
					enable_document_encryption = '".$enable_document_encryption."',
					enable_anonymous_document_links = '".$enable_anonymous_document_links."',
					theme = '".EscapeData($theme)."'
					WHERE workspace_id = ".$GLOBALS['workspace_id']."
					AND teamspace_id ".$GLOBALS['teamspace_sql']."
				";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->AddSettings();
			$this->Edit($show_document_rating);
			//$this->Errors("Changes not saved");
			//return False;
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