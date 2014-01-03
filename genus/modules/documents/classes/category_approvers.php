<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class CategoryApprovers {

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

		if ($this->parameter_check && !$this->CategoryApproverExists()) {

			$sql="INSERT INTO ".$GLOBALS['database_prefix']."document_categories_approvers
						(category_id, user_id)
						VALUES (
						".$this->category_id.",
						".$this->user_id."
						)";
			//echo $sql;
			$result=$db->query($sql);
			if ($result) {
				$this->SetCategoryApprovalChar();
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

			$sql="DELETE FROM ".$GLOBALS['database_prefix']."document_categories_approvers
						WHERE category_id = ".$this->category_id."
						AND user_id = ".$this->user_id."
						";
			$result=$db->query($sql);
			if ($result) {
				$this->SetCategoryApprovalChar();
				return True;
			}
			else {
				return False;
			}
		}
	}

	public function CategoryApproverExists() {

		$db=$GLOBALS['db'];
		//echo "OK-OK";
		if ($this->parameter_check) {
			//echo "Ok entering cat user priv<br>";
			$sql="SELECT 'x'
						FROM ".$GLOBALS['database_prefix']."document_categories_approvers
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

	public function SetCategoryApprovalChar() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			//echo "Ok entering cat user priv<br>";
			$sql="SELECT count(*) as total
						FROM ".$GLOBALS['database_prefix']."document_categories_approvers
						WHERE category_id = ".$this->category_id."
						";
			$result=$db->query($sql);
			if ($db->NumRows($result) > 0) {
				while($row = $db->FetchArray($result)) {
					if ($row['total'] > 0) {
						/* MORE THAN 1 APPROVER - THEREFORE CATEGORY REQUIRES APPROVAL */
						$sql_upd="UPDATE ".$GLOBALS['database_prefix']."document_categories
											SET requires_approval = 'y'
											WHERE category_id = ".$this->category_id."
										";
						$result=$db->query($sql_upd);
					}
					else {
						/* NO APPROVAL REQUIRED */
						$sql_upd="UPDATE ".$GLOBALS['database_prefix']."document_categories
											SET requires_approval = 'n'
											WHERE category_id = ".$this->category_id."
										";
						$result=$db->query($sql_upd);
					}
					return True;
				}
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