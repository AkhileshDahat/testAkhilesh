<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class WorkspaceID {

	function __construct() {

	}

	public function SetParameters($workspace_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($workspace_id)) { $this->Errors("Invalid Workspace"); return False; }
		if (EMPTY($_SESSION['user_id'])) { $this->Errors("Logged out"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->workspace_id=$workspace_id;

		/* PRIVILEGE */
		if (!$this->CheckACL()) { $this->Errors("Access Denied"); return False; }

		/* CALL THE DOCUMENTID INFORMATION - WE NEED THIS BEFORE RUNNING THE BELOW CHECK ON SECURITY*/
		$this->Info();

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	private function Info() {

		$db=$GLOBALS['db'];

		$sql="SELECT wm.*,
					wsm.status_name
					FROM ".$GLOBALS['database_prefix']."core_workspace_master wm, ".$GLOBALS['database_prefix']."core_space_status_master wsm
					WHERE wm.workspace_id = '".$this->workspace_id."'
					AND wm.status_id = wsm.status_id
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

	public function CheckACL() {
		$db=$GLOBALS['db'];

		$sql="SELECT 'x'
					FROM ".$GLOBALS['database_prefix']."core_space_users wu,".$GLOBALS['database_prefix']."core_workspace_role_master cwrm
					WHERE wu.workspace_id = ".$this->workspace_id."
					AND wu.user_id = ".$_SESSION['user_id']."
					AND wu.role_id = cwrm.role_id
					AND cwrm.manage_workspaces = 'y'
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

	/* GET A COLUMN NAME FROM THE ARRAY */
	public function GetColVal($col_name) {
		if (ISSET($this->$col_name)) {
			return $this->$col_name;
		}
		else {
			return false;
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