<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class RoleID {

	function __construct() {

		$this->errors="";
	}
	public function SetParameters($role_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($role_id)) { $this->Errors("Invalid role"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->role_id=$role_id;

		/* CALL THE ROLE ID INFORMATION */
		$this->Info($role_id);

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	private function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."core_role_master
					WHERE role_id = '".$this->role_id."'
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
		if (ISSET($this->$v)) {
			return $this->$v;
		}
		else {
			return false;
		}
	}

	public function Add($role_name) {

		/* CHECKS */
		if(!preg_match("([a-zA-Z0-9])",$role_name))  { $this->Errors("Invalid role name. Please use only alpha-numeric values!"); return False; }
		if (RowExists("core_role_master","role_name","'".$role_name."'","")) { $this->Errors("Role name exists. Please choose another."); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_role_master
					(role_name)
					VALUES (
					'".$role_name."'
					)";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				LogHistory($role_name." added");
				return True;
		}
		else {
			return False;
		}
	}

	public function Edit($role_name) {

		/* CHECKS */
		if (!$this->parameter_check) { $this->Errors("Parameter check failed"); return False; }
		if(!preg_match("([a-zA-Z0-9])",$role_name))  { $this->Errors("Invalid role name. Please use only alpha-numeric values!"); return False; }
		if (RowExists("core_role_master","role_name","'".$role_name."'","")) { $this->Errors("Role name exists. Please choose another."); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="UPDATE ".$GLOBALS['database_prefix']."core_role_master
					SET role_name = '".$role_name."'
					WHERE role_id = ".$this->role_id."
					";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				LogHistory($this->role_name." edited");
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
						FROM ".$GLOBALS['database_prefix']."core_role_master
						WHERE role_id = ".$this->role_id."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				LogHistory($this->role_name." deleted");
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

	public function ChangeCreateWorkspace() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			if ($this->GetInfo("create_workspace") == "y") {
				$create_workspace = "n";
			}
			else {
				$create_workspace = "y";
			}
			$sql="UPDATE ".$GLOBALS['database_prefix']."core_role_master
						SET create_workspace = '$create_workspace'
						WHERE role_id = ".$this->role_id."
						AND role_id != 1
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				//LogHistory($this->period_name." deleted");
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

	public function ChangeManageWorkspace() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			if ($this->GetInfo("manage_core_workspaces") == "y") {
				$manage_core_workspaces = "n";
			}
			else {
				$manage_core_workspaces = "y";
			}
			$sql="UPDATE ".$GLOBALS['database_prefix']."core_role_master
						SET manage_core_workspaces = '$manage_core_workspaces'
						WHERE role_id = ".$this->role_id."
						AND role_id != 1
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				//LogHistory($this->period_name." deleted");
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

	public function ChangeManageUsers() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			if ($this->GetInfo("manage_core_users") == "y") {
				$manage_core_users = "n";
			}
			else {
				$manage_core_users = "y";
			}
			$sql="UPDATE ".$GLOBALS['database_prefix']."core_role_master
						SET manage_core_users = '$manage_core_users'
						WHERE role_id = ".$this->role_id."
						AND role_id != 1
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				//LogHistory($this->period_name." deleted");
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

	public function ChangeManageRoles() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			if ($this->GetInfo("create_workspace_roles") == "y") {
				$create_workspace_roles = "n";
			}
			else {
				$create_workspace_roles = "y";
			}
			$sql="UPDATE ".$GLOBALS['database_prefix']."core_role_master
						SET create_workspace_roles = '$create_workspace_roles'
						WHERE role_id = ".$this->role_id."
						AND role_id != 1
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				//LogHistory($this->period_name." deleted");
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