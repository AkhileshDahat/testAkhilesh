<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."modules/documents/functions/log_history.php";
require_once $dr."include/functions/db/get_sequence_currval.php";

class AddCategory {

	/* GET THE DEFAULT PARAMETERS AND DO SOME CHECKING */
	public function SetParameters($category_name) {

		/* SET THIS TO FALSE */
		$this->parameter_check=False;

		/* DATA CHECKING */
		if(!preg_match("([a-zA-Z0-9]*)",$category_name))  { $this->Errors("Invalid category name. Please use only alpha-numeic valued!"); return False; }
		if (EMPTY($_SESSION['user_id'])) { $this->Errors("Invalid User. Please login"); return False; }
		//if (EMPTY($GLOBALS['wui']->RoleID())) { $this->Errors("No role assigned. You cannot proceed."); return False; }

		/* SET THE VARIABLE IN GLOBAL SCOPE */
		$this->category_name=$category_name;
		$this->role_id=$GLOBALS['ui']->RoleID();

		/* SET THIS TO TRUE */
		$this->parameter_check=True;
	}

	public function Add() {

		if (!$this->parameter_check) { $this->Errors("Parameter check failed!"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		/* INSERT TO THE DATABASE */
		$sql="INSERT INTO ".$GLOBALS['database_prefix']."document_categories
					(parent_id, category_name, workspace_id, teamspace_id)
					VALUES (
					".$parent_id.",
					'".$category_name."',
					".$workspace_id.",
					".$teamspace_id."
					)";
		$result=$db->query($sql);
		if ($result) {
			$this->category_id=GetSequenceCurrval("s_document_categories_category_id");
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
			$
			return True;
		}
		else {
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