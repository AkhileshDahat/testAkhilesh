<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class CategoryID {

	function __construct() {

		$this->errors="";
	}
	public function SetParameters($category_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($category_id)) { $this->Errors("Invalid department"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->category_id=$category_id;

		/* CALL THE departmentID INFORMATION */
		$this->Info($category_id);

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	private function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT category_name
					FROM ".$GLOBALS['database_prefix']."project_category_master
					WHERE category_id = '".$this->category_id."'
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

	public function Add() {

		/* CHECKS */
		if (EMPTY($this->category_name)) { $this->Errors("Empty category name!"); return False; }
		if(!preg_match("([a-zA-Z0-9])",$this->category_name))  { $this->Errors("Invalid department name. Please use only alpha-numeric values!"); return False; }
		if (RowExists("project_category_master","category_name","'".$this->category_name."'","AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("department name exists. Please choose another."); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."project_category_master
					(category_name,workspace_id,teamspace_id)
					VALUES (
					'".$this->category_name."',
					".$GLOBALS['workspace_id'].",
					".$GLOBALS['teamspace_id']."
					)";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				//LogHistory($category_name." added");
				return True;
		}
		else {
			return False;
		}
	}

	public function Edit($category_name) {

		/* CHECKS */
		if (!$this->parameter_check) { $this->Errors("Parameter check failed"); return False; }
		if(!preg_match("([a-zA-Z0-9])",$category_name))  { $this->Errors("Invalid department name. Please use only alpha-numeric values!"); return False; }
		if (RowExists("project_category_master","category_name","'".$category_name."'","AND workspace_id=".$GLOBALS['workspace_id'])) { $this->Errors("department name exists. Please choose another."); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="UPDATE ".$GLOBALS['database_prefix']."project_category_master
					SET category_name = '".$category_name."'
					WHERE category_id = ".$this->category_id."
					AND workspace_id = ".$GLOBALS['workspace_id']."
					AND teamspace_id ".$GLOBALS['teamspace_sql']."
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
						FROM ".$GLOBALS['database_prefix']."project_category_master
						WHERE category_id = ".$this->category_id."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				//LogHistory($this->category_name." deleted");
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
	
	public function GetFormPostedValues() {
		$all_form_fields=array("category_name","category_id");

		for ($i=0;$i<count($all_form_fields);$i++) {

			//echo $_POST['application_id'];
			if (ISSET($_POST[$all_form_fields[$i]]) && !EMPTY($_POST[$all_form_fields[$i]])) {
				$this->SetVariable($all_form_fields[$i],$_POST[$all_form_fields[$i]]);
			}
			else {
				//echo "<br>".$all_form_fields[$i]."<br>";
				$this->$all_form_fields[$i]="";
			}
		}
	}
	
	public function SetVariable($variable,$variable_val) {
		$this->$variable=EscapeData($variable_val);
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}
}
?>