<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class WorkspaceUsers {

	function __construct() {

	}

	public function SetParameters($workspace_id,$user_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($workspace_id)) { $this->Errors("Invalid Workspace"); return False; }
		if (!IS_NUMERIC($user_id)) { $this->Errors("Invalid User"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->workspace_id=$workspace_id;
		$this->user_id=$user_id;

		/* CALL THE DOCUMENTID INFORMATION - WE NEED THIS BEFORE RUNNING THE BELOW CHECK ON SECURITY*/
		$this->Info();

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	private function Info() {

		$db=$GLOBALS['db'];

		$sql="SELECT wu.role_id, wu.theme
					FROM ".$GLOBALS['database_prefix']."core_space_users wu
					WHERE wu.workspace_id = '".$this->workspace_id."'
					AND wu.user_id = '".$this->user_id."'
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
	/* ADD A USER */
	public function AddUser() {

		if ($this->parameter_check) {
			$db=$GLOBALS['db'];

			$role_id=GetColumnValue("role_id","core_role_master","default_role","y","");

			$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_space_users (user_id,workspace_id,role_id)
						VALUES (
						'".$this->user_id."',
						'".$this->workspace_id."',
						'".$role_id."'
						)";
			//echo $sql."<br>";
			$result = $db->Query($sql);
			if ($db->AffectedRows($result) > 0) {
				return True;
			}
			else {
				$this->Errors("Could not assign default privileges.");
				return False;
			}
		}
		else {
			return False;
		}
	}

	/* EDIT A USER */
	public function EditUser($role_id) {

		if ($this->parameter_check) {
			$db=$GLOBALS['db'];

			$sql="UPDATE ".$GLOBALS['database_prefix']."core_space_users
						SET role_id = '".$role_id."'
						WHERE user_id = '".$this->user_id."'
						AND workspace_id = '".$this->workspace_id."'
						";
			echo $sql."<br>";
			$result = $db->Query($sql);
			if ($db->AffectedRows($result) > 0) {
				return True;
			}
			else {
				$this->Errors("No changes recorded.");
				return True;
				//return False; // THIS MIGHT NOT BE CHANGED SO DO NOT RETURN A FALSE HERE
			}
		}
		else {
			$this->Errors("Parameter check failed");
			return False;
		}
	}

	/* DELETE A USER */
	public function DeleteUser() {

		if ($this->parameter_check) {
			$db=$GLOBALS['db'];

			$sql="DELETE FROM ".$GLOBALS['database_prefix']."core_space_users
						WHERE user_id = '".$this->user_id."'
						AND workspace_id = '".$this->workspace_id."'
						";
			//echo $sql."<br>";
			$result = $db->Query($sql);
			if ($db->AffectedRows($result) > 0) {
				return True;
			}
			else {
				$this->Errors("Unable to delete user.");
				return False;
			}
		}
		else {
			return False;
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