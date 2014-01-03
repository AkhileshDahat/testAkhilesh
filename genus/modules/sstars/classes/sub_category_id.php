<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class SubCategoryID {

	function __construct() {

	}
	public function SetParameters($sub_category_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($sub_category_id)) { $this->Errors("Invalid sub category"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->sub_category_id=$sub_category_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info($sub_category_id);

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	private function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT scm.sub_category_id,scm.sub_category_name,cm.category_name,'edit' AS edit,'delete' AS del
					FROM ".$GLOBALS['database_prefix']."sstars_sub_category_master scm, ".$GLOBALS['database_prefix']."sstars_category_master cm
					WHERE sub_category_id = '".$this->sub_category_id."'
					AND scm.workspace_id = ".$GLOBALS['workspace_id']."
					AND scm.teamspace_id ".$GLOBALS['teamspace_sql']."
					AND scm.category_id = cm.category_id
					ORDER BY cm.category_name
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

	public function Add($sub_category_name,$category_id) {

		/* CHECKS */
		if(!preg_match("([a-zA-Z0-9])",$sub_category_name))  { $this->Errors("Invalid sub-category name. Please use only alpha-numeric values!"); return False; }
		if (RowExists("sstars_sub_category_master","sub_category_name","'".$sub_category_name."'","AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Category name exists. Please choose another."); return False; }
		if (!IS_NUMERIC($category_id)) { $this->Errors("Invalid category id."); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."sstars_sub_category_master
					(sub_category_name,category_id,workspace_id,teamspace_id)
					VALUES (
					'".$sub_category_name."',
					".$category_id.",
					".$GLOBALS['workspace_id'].",
					".$GLOBALS['teamspace_id']."
					)";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				LogHistory($sub_category_name." added");
				return True;
		}
		else {
			return False;
		}
	}

	public function Edit($sub_category_name) {

		/* CHECKS */
		if (!$this->parameter_check) { $this->Errors("Parameter check failed"); return False; }
		if(!preg_match("([a-zA-Z0-9])",$sub_category_name))  { $this->Errors("Invalid category name. Please use only alpha-numeric values!"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="UPDATE ".$GLOBALS['database_prefix']."sstars_sub_category_master
					SET sub_category_name = '".$sub_category_name."'
					WHERE sub_category_id = ".$this->sub_category_id."
					AND workspace_id = ".$GLOBALS['workspace_id']."
					AND teamspace_id ".$GLOBALS['teamspace_sql']."
					";
		//echo $sql."<br>";
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
						FROM ".$GLOBALS['database_prefix']."sstars_sub_category_master
						WHERE sub_category_id = ".$this->sub_category_id."
						AND workspace_id = ".$GLOBALS['workspace_id']."
						AND teamspace_id ".$GLOBALS['teamspace_sql']."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				LogHistory($this->sub_category_name." deleted");
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