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

		/* SET SOME COMMON VARIABLES */
		$this->workspace_id=$workspace_id;

		/* CALL THE DOCUMENTID INFORMATION - WE NEED THIS BEFORE RUNNING THE BELOW CHECK ON SECURITY*/
		$this->Info();

		/* PRIVILEGE - NOT IMPLEMENTED */
		if (!$this->CheckACL()) { $this->Errors("Access Denied"); return False; }

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	private function Info() {

		$db=$GLOBALS['db'];

		$sql="SELECT wm.workspace_code, wm.name, wm.description, wm.logo, wm.max_teamspaces, wm.max_size, wm.max_users,
					wm.date_start, wm.date_end, wm.status_id, wm.enterprise,
					wsm.status_name
					FROM ".$GLOBALS['database_prefix']."workspace_master wm, ".$GLOBALS['database_prefix']."workspace_status_master wsm
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
					FROM ".$GLOBALS['database_prefix']."workspace_users wu,".$GLOBALS['database_prefix']."core_role_master crm
					WHERE wu.workspace_id = ".$this->workspace_id."
					AND wu.user_id = ".$_SESSION['user_id']."
					AND wu.role_id = crm.role_id
					AND crm.manage_workspaces = 'y'
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