<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

//require_once $GLOBALS['dr']."include/functions/db/get_sequence_currval.php";

class CategoryID {

	function __construct() {
		$this->errors="";
	}

	public function SetParameters($category_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($category_id)) { $this->Errors("Invalid Category"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->category_id=$category_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info($category_id);

		/* PRIVILEGE - remove because we need to use the security function */
		//if (!$this->CategoryRoleExists()) { $this->Errors("Access to category denied."); return False; }

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function Info($category_id) {

		$this->db=$GLOBALS['db'];

		$sql="SELECT dc.parent_id, dc.category_name, dc.category_description, dc.workspace_id, dc.teamspace_id,
					dc.requires_approval, dc.locked
					FROM ".$GLOBALS['database_prefix']."document_categories dc
					WHERE dc.category_id = '".$this->category_id."'
					";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				$this->parent_id=$row["parent_id"];
				$this->category_name=$row["category_name"];
				$this->category_description=$row["category_description"];
				$this->workspace_id=$row["workspace_id"];
				$this->teamspace_id=$row["teamspace_id"];
				$this->requires_approval=$row["requires_approval"];
				$this->locked=$row["locked"];
			}
		}
	}
	/*
		THIS FUNCTION DETERMINES IF THE USER, IN THE CURRENT WORKSPACE ROLE, HAS ACCESS TO THE CATEGORY
	*/
	public function CategoryRoleExists() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			$sql="SELECT 'x'
						FROM ".$GLOBALS['database_prefix']."document_category_role_security
						WHERE category_id = ".$this->category_id."
						AND role_id = ".$GLOBALS['wui']->RoleID()."
						";
			$result=$db->query($sql);
			if ($db->NumRows($result) > 0) {
				return True;
			}
			else {
				return False;
			}
		}
		else {
			$this->Errors("Sorry, document category role parameter check failed<br>");
			return False;
		}
	}

	public function GetInfo($v) {
		return $this->$v;
	}

	public function Add($parent_id,$category_name) {

		/* CHECKS */
		if (!IS_NUMERIC($parent_id)) { $this->Errors("Invalid Folder"); return False; }
		if (EMPTY($category_name)) { $this->Errors("No category entered"); return False; }
		if(!preg_match("([a-zA-Z0-9])",$category_name))  { $this->Errors("Invalid category name. Please use only alpha-numeric values!"); return False; }
		if ($this->CategoryExists($category_name,$parent_id)) { $this->Errors("Category exists"); return False; }
		if (EMPTY($_SESSION['user_id'])) { $this->Errors("Invalid User. Please login"); return False; }
		//echo "Role ID".$GLOBALS['ui']->RoleID();
		//if (EMPTY($GLOBALS['ui']->RoleID())) { $this->Errors("No role assigned. You cannot proceed."); return False; }

		/* SET THE VARIABLE IN GLOBAL SCOPE */
		$this->category_name=$category_name;
		$this->role_id=$GLOBALS['wui']->RoleID();

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."document_categories
					(parent_id, category_name, workspace_id, teamspace_id)
					VALUES (
					".$parent_id.",
					'".EscapeData($category_name)."',
					".$GLOBALS['ui']->WorkspaceID().",
					".$GLOBALS['teamspace_id']."
					)";
		$result=$db->query($sql);
		if ($result) {
			$this->category_id=$db->LastInsertId("s_document_categories_category_id");
			if ($this->AddCategorySecurity()) {
				return True;
			}
			else {
				return False;
			}
		}
		else {
			return False;
		}
	}

	public function Delete() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			$sql="DELETE
						FROM ".$GLOBALS['database_prefix']."document_categories
						WHERE category_id = ".$this->category_id."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				return True;
			}
			else {
				$this->Errors("Failed to delete");
				return False;
			}
		}
		else {
			$this->Errors("Sorry, document category role parameter check failed<br>");
			return False;
		}
	}


	public function CategoryExists($par_category_name,$par_parent_id) {

		/* CHECKS */
		if (EMPTY($par_category_name)) { $this->Errors("Invalid Category Name"); return False; }
		if (!IS_NUMERIC($par_parent_id)) { $this->Errors("Invalid Parent"); return False; }

		$db=$GLOBALS['db'];

		$sql="SELECT 'x'
					FROM ".$GLOBALS['database_prefix']."document_categories dc
					WHERE dc.category_name = '".EscapeData($par_category_name)."'
					AND dc.parent_id = ".$par_parent_id."
					AND dc.workspace_id = ".$GLOBALS['workspace_id']."
					AND dc.teamspace_id ".$GLOBALS['teamspace_sql']."
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			return True;
		}
		else {
			return False;
		}
	}

	private function AddCategorySecurity() {

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		/* INSERT TO THE DATABASE */
		$sql="INSERT INTO ".$GLOBALS['database_prefix']."document_category_role_security
					(category_id,role_id)
					VALUES (
					".$this->category_id.",
					".$this->role_id."
					)";
		$result=$db->query($sql);
		if ($result) {
			return True;
		}
		else {
			return False;
		}
	}

	private function GenerateCategoryHeirarchy($par_parent_id) {

		/* PUSH ITEMS TO OUR CATEGORY ID ARRAY BEFORE WE SELECT THE PARENT */
		array_push($this->heirarchy_array_id,$par_parent_id);

		$db=$GLOBALS['db'];

		$sql="SELECT parent_id, category_name
					FROM ".$GLOBALS['database_prefix']."document_categories dc
					WHERE dc.category_id = '".$par_parent_id."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				array_push($this->heirarchy_array,$row['category_name']);
				$this->GenerateCategoryHeirarchy($row['parent_id']);
			}
		}
		else {
			//return $this->heirarchy_array;
		}
	}

	function CategoryHeirarchy($par_parent_id) {

		if (!IS_NUMERIC($par_parent_id)) { $this->Errors("Invalid Parent"); return False; }

		$c="";
		$this->heirarchy_array[]="";
		$this->heirarchy_array_id[]="";

		$this->GenerateCategoryHeirarchy($par_parent_id);

		/* REVERSE THE NAMES OF EACH CATEGORY*/
		$this->heirarchy_array=array_reverse($this->heirarchy_array);

		/* FORMAT ALL THE CATEGORIES IN THE REVERSE ORDER */
		$this->heirarchy_array_id=array_reverse($this->heirarchy_array_id);

		for ($i=0;$i<count($this->heirarchy_array)-1;$i++) {
			$c.="<a href='index.php?module=documents&task=home&parent_id=".$this->heirarchy_array_id[($i+1)]."'>".$this->heirarchy_array[$i]."</a> -> ";
		}
		/* REMOVE THE FINAL ARROW */
		return SUBSTR($c, 0, -3);
	}

	public function CountFiles($par_category_id) {
		//print_r ($this->heirarchy_array);
		//echo "<br>";

		$db=$GLOBALS['db'];

		$sql="SELECT count(*) AS total
					FROM ".$GLOBALS['database_prefix']."document_files
					WHERE category_id = '".$par_category_id."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				return $row['total'];
			}
		}
		else {
			return 0;
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