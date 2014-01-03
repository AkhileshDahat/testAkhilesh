<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class CategoryUserSecurity {

	public function SetParameters($category_id,$user_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* DATA CHECKING */
		if (!IS_NUMERIC($category_id)) { $this->Errors("Invalid category. Non numeric value!"); return False; }
		if (!IS_NUMERIC($user_id)) { $this->Errors("Invalid user. Non numeric value!"); return False; }
		if (EMPTY($_SESSION['user_id'])) { $this->Errors("Invalid User. Please login"); return False; }

		/* SET THE VARIABLE IN GLOBAL SCOPE */
		$this->category_id=$category_id;
		$this->user_id=$user_id;

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;
	}

	public function Add() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check && !$this->CategoryUserExists()) {

			$sql="INSERT INTO ".$GLOBALS['database_prefix']."document_category_user_security
						(category_id, user_id)
						VALUES (
						".$this->category_id.",
						".$this->user_id."
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

			$sql="DELETE FROM ".$GLOBALS['database_prefix']."document_category_user_security
						WHERE category_id = ".$this->category_id."
						AND user_id = ".$this->user_id."
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

	public function CategoryUserExists() {

		$db=$GLOBALS['db'];
		//echo "OK-OK";
		if ($this->parameter_check) {
			//echo "Ok entering cat user priv<br>";
			$sql="SELECT 'x'
						FROM ".$GLOBALS['database_prefix']."document_category_user_security
						WHERE category_id = ".$this->category_id."
						AND user_id = ".$this->user_id."
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
			$this->Errors("Sorry, document category user parameter check failed<br>");
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