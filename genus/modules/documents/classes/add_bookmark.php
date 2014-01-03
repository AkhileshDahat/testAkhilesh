<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/documents/classes/category_role_security.php";

class AddBookmark {

	/* GET THE DEFAULT PARAMETERS AND DO SOME CHECKING */
	public function SetParameters($category_id) {

		/* SET THIS TO FALSE */
		$this->parameter_check=False;

		/* DATA CHECKING */
		if (!IS_NUMERIC($category_id)) { $this->Errors("Invalid category"); return False; }
		if (!RowExists("document_categories","category_id",$category_id,"AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Category does not exist"); return False; }
		if (EMPTY($_SESSION['user_id'])) { $this->Errors("Invalid User. Please login"); return False; }

		/* CHECK CATEGORY SECURITY */
		$crs=new CategoryRoleSecurity;
		$crs->SetParameters($category_id,$GLOBALS['wui']->role_id);
		if (!$crs->CategoryRoleExists()) { $this->Errors("Access denied to this category"); return False; }

		/* SET THE VARIABLE IN GLOBAL SCOPE */
		$this->category_id=$category_id;

		/* SET THIS TO TRUE */
		$this->parameter_check=True;
	}

	public function Add() {

		if (!$this->parameter_check) { $this->Errors("Parameter check failed!"); return False; }
		if ($this->BookmarkExists($_SESSION['user_id'],$this->category_id)) { $this->Errors("Bookmark exists!"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		/* INSERT TO THE DATABASE */
		$sql="INSERT INTO ".$GLOBALS['database_prefix']."document_user_bookmarks
					VALUES (
					".$_SESSION['user_id'].",
					".$this->category_id."
					)";
		$result=$db->query($sql);
		if ($result) {
			return True;
		}
		else {
			return False;
		}
	}

	public function BookmarkExists($user_id,$category_id) {
		if (RowExists("document_user_bookmarks","user_id",$user_id,"AND category_id=".$category_id)) {
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