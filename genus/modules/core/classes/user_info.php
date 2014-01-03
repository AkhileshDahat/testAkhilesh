<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class UserInfo {

	function UserInfo($id) {

		$db=$GLOBALS['db'];

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."core_user_master um, ".$GLOBALS['database_prefix']."core_role_master rm
					WHERE um.user_id = '".$id."'
					AND um.role_id = rm.role_id
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

	public function GetInfo($v) {
		if (ISSET($this->$v)) {
			return $this->$v;
		}
	}

	function Login() {	return $this->login; }
	function Password() { return $this->password;	}
	function TitleID() {	return $this->title_id; }
	function SessionID() { return $this->session_id; }
	function WorkspaceID() { if (ISSET($this->workspace_id)) {return $this->workspace_id; } else { return false; } }
	function TeamspaceID() { if (ISSET($this->teamspace_id)) {return $this->teamspace_id; } else { return false; } }

	function FullName() { return $this->full_name; }
	function Photograph() { return $this->photograph; }
	function Timezone() { if (ISSET($this->timezone)) {return $this->timezone; } else { return false; } }
	/* ROLE PRIVILEGES */
	function CreateWorkspace() { return $this->create_workspace; }
	//function BrowseWorkspaces() { return $this->browse_workspaces; }
	/* ROLE */
	function RoleID() { return $this->role_id; }
	function RoleName() { if (ISSET($this->role_name)) {return $this->role_name; } else { return false; } }

	function CreateMaxWorkspaces() { return $this->create_max_workspaces; }
	function CreateWorkspacesRoles() { return $this->create_workspace_roles; }

	/* THIS NEEDS TO BE CLEANED UP AND REFERENCED FROM HRMS MODULE CLASSES */
	public function SuperiorID() {
		$db=$GLOBALS['db'];
		$sql="SELECT superior_user_id
					FROM ".$GLOBALS['database_prefix']."hrms_user_reporting
					WHERE subordinate_user_id = '".$_SESSION['user_id']."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				return $row['superior_user_id'];
			}
		}
		else {
			return False;
		}
	}
}
?>