<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."modules/groups/functions/exists/group_name_exists.php";

class DeleteUserModule {

	public function Delete($user_id,$workspace_id,$module_id) {

		if (IS_NUMERIC($user_id)) { $this->Errors("User Invalid"); return False; }
		if (IS_NUMERIC($workspace_id)) { $this->Errors("Workspace Invalid"); return False; }
		if (IS_NUMERIC($module_id)) { $this->Errors("Module Invalid"); return False; }

		$this->db=$GLOBALS['db'];

		$sql="DELETE FROM ".$GLOBALS['database_prefix']."core_space_user_modules
					WHERE user_id = $user_id
					AND workspace_id = $workspace_id
					AND module_id = $module_id
					";
		$result = $db->Query($sql);

		if ($db->AffectedRows() > 0) {
			return True;
		}
		else {
			$this->Errors("Failed to delete user module");
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