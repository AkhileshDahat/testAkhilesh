<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class WorkspaceUserInfo {

	function WorkspaceUserInfo($user_id,$workspace_id) {

		$db=$GLOBALS['db'];

		$sql="SELECT wu.role_id,wu.theme,cwrm.role_name,cwrm.create_teamspace
					FROM ".$GLOBALS['database_prefix']."core_space_users wu, ".$GLOBALS['database_prefix']."core_workspace_role_master cwrm
					WHERE wu.user_id = '".$user_id."'
					AND wu.workspace_id = '".$workspace_id."'
					AND wu.role_id = cwrm.role_id
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

	/* GET A COLUMN NAME FROM THE ARRAY */
	public function GetColVal($col_name) {
		return $this->$col_name;
	}

	/* LEGACY STUFF */
	function RoleID() {	return $this->role_id; }
	function Theme() {	return $this->theme; }
	function RoleName() {	return $this->role_name; }
	function CreateTeamspace() {	return $this->create_teamspace; }
}
?>