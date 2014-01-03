<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class CategoryRoleSecurity {

	public function SetParameters($category_id,$role_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* DATA CHECKING */
		if (!IS_NUMERIC($category_id)) { $this->Errors("Invalid category. Non numeric value!"); return False; }
		if (!IS_NUMERIC($role_id)) { $this->Errors("Invalid role. Non numeric value!"); return False; }
		if (EMPTY($_SESSION['user_id'])) { $this->Errors("Invalid User. Please login"); return False; }

		/* SET THE VARIABLE IN GLOBAL SCOPE */
		$this->category_id=$category_id;
		$this->role_id=$role_id;

		/* GRAB ALL THE INFO */
		$this->Info();

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	private function Info() {

		$db=$GLOBALS['db'];

		$sql="SELECT browse,upload,delete_files
					FROM ".$GLOBALS['database_prefix']."document_category_role_security
					WHERE category_id = '".$this->category_id."'
					AND role_id = '".$this->role_id."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				/* HERE WE CALL THE FIELDS AND SET THEM INTO DYNAMIC VARIABLES */
				$arr_cols=$db->GetColumns($result);
				for ($i=1;$i<count($arr_cols);$i++) {
					$col_name=$arr_cols[$i];
					$this->$col_name=$row[$col_name];
				}
			}
		}
	}

	public function Add() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check && !$this->CategoryRoleExists()) {

			$sql="INSERT INTO ".$GLOBALS['database_prefix']."document_category_role_security
						(category_id, role_id)
						VALUES (
						".$this->category_id.",
						".$this->role_id."
						)";
			//echo $sql;
			$result=$db->query($sql);
			if ($result) {
				return True;
			}
			else {
				return False;
			}
		}
	}

	public function Delete() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {

			$sql="DELETE FROM ".$GLOBALS['database_prefix']."document_category_role_security
						WHERE category_id = ".$this->category_id."
						AND role_id = ".$this->role_id."
						";
			$result=$db->query($sql);
			if ($result) {
				return True;
			}
			else {
				return False;
			}
		}
	}

	public function CategoryRoleExists() {

		$db=$GLOBALS['db'];
		//echo "OK-OK";
		if ($this->parameter_check) {
			//echo "Ok entering cat role priv<br>";
			$sql="SELECT 'x'
						FROM ".$GLOBALS['database_prefix']."document_category_role_security
						WHERE category_id = ".$this->category_id."
						AND role_id = ".$this->role_id."
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

	public function AddAdvancedSecurity($col,$val) {
		$db=$GLOBALS['db'];
		//echo "OK-OK";
		if (!$val=="y" || !$val=="n") { $this->Errors("Invalid response"); return False; }
		if ($this->parameter_check) {
			//echo "Ok entering cat role priv<br>";
			$sql="UPDATE ".$GLOBALS['database_prefix']."document_category_role_security
						SET $col = '".$val."'
						WHERE category_id = ".$this->category_id."
						AND role_id = ".$this->role_id."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				return True;
			}
			else {
				return False;
			}
		}
	}

	/* GET A COLUMN NAME FROM THE ARRAY */
	public function GetColVal($col_name) {
		return $this->$col_name;
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}
}
?>