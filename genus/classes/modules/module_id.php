<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class ModuleID {

	public function __construct() {
		//$this->Info();
	}

	public function Info($module_id) {
		$db=$GLOBALS['db'];

		$this->module_id=$module_id;
		if (!IS_NUMERIC($module_id)) { $this->Errors("Non numeric module ID"); return False; }

		$sql="SELECT name,description,available_teamspaces,logo,signup_module,introduction,available_all_workspaces,anonymous_access
					FROM ".$GLOBALS['database_prefix']."core_module_master
					WHERE module_id = ".$this->module_id."
					";
		//echo $sql;
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
		return True;
	}

	public function CheckACL() {
		$db=$GLOBALS['db'];

		/* IF THE MODULE ALLOWS ANONYMOUS ACCESS THEN OKAY - THIS IS FROM THE CORE_MODULE_MASTER TABLE */
		if ($this->anonymous_access=="y") { return True; }
		/* IF NO WORKSPACE_ID OR ROLE_ID THEN RETURN FALSE - IN THE EVENT OF PUBLIC MODULES WHICH ARE NOT ANONYMOUS - IMPOSSIBLE COMBINATION */
		if (!ISSET($GLOBALS['workspace_id']) || !ISSET($GLOBALS['wui'])) { return False; }

		$sql="SELECT 'x'
					FROM ".$GLOBALS['database_prefix']."core_space_module_acl
					WHERE workspace_id = ".$GLOBALS['workspace_id']."
					AND	module_id = ".$this->module_id."
					AND role_id = ".$GLOBALS['wui']->GetColVal("role_id")."
					";
		//echo $sql;
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			return True;
		}
		else {
			return False;
		}
	}

	public function GetInfo($v) {
		return $this->$v;
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}

}
?>