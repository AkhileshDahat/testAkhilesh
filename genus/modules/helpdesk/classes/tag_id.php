<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class TagID {

	function __construct() {

		$this->errors="";
	}
	public function SetParameters($tag_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($tag_id)) { $this->Errors("Invalid tag"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->tag_id=$tag_id;

		/* CALL THE tagID INFORMATION */
		$this->Info($tag_id);

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	private function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT tag_name
					FROM ".$GLOBALS['database_prefix']."helpdesk_tag_master
					WHERE tag_id = '".$this->tag_id."'
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

	public function GetInfo($v) {
		return $this->$v;
	}

	public function Add($tag_name) {

		/* CHECKS */

		if(!preg_match("([a-zA-Z0-9])",$tag_name))  { $this->Errors("Invalid tag name. Please use only alpha-numeric values!"); return False; }
		if (RowExists("helpdesk_tag_master","tag_name","'".$tag_name."'","AND workspace_id=".$GLOBALS['workspace_id'])) { $this->Errors("tag name exists. Please choose another."); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."helpdesk_tag_master
					(tag_name,workspace_id,teamspace_id)
					VALUES (
					'".$tag_name."',
					".$GLOBALS['workspace_id'].",
					".$GLOBALS['teamspace_id']."
					)";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				//LogHistory($tag_name." added");
				return True;
		}
		else {
			return False;
		}
	}

	public function Edit($tag_name) {

		/* CHECKS */
		if (!$this->parameter_check) { $this->Errors("Parameter check failed"); return False; }
		if(!preg_match("([a-zA-Z0-9])",$tag_name))  { $this->Errors("Invalid tag name. Please use only alpha-numeric values!"); return False; }
		if (RowExists("helpdesk_tag_master","tag_name","'".$tag_name."'","AND workspace_id=".$GLOBALS['workspace_id'])) { $this->Errors("tag name exists. Please choose another."); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="UPDATE ".$GLOBALS['database_prefix']."helpdesk_tag_master
					SET tag_name = '".$tag_name."'
					WHERE tag_id = ".$this->tag_id."
					";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				return True;
		}
		else {
			return False;
		}
	}

	public function Delete() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			$sql="DELETE
						FROM ".$GLOBALS['database_prefix']."helpdesk_tag_master
						WHERE tag_id = ".$this->tag_id."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				//LogHistory($this->tag_name." deleted");
				return True;
			}
			else {
				$this->Errors("Failed to delete");
				return False;
			}
		}
		else {
			$this->Errors("Sorry, parameter check failed<br>");
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