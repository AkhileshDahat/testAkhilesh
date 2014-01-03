<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/misc/log_history.php";

class DeleteWorkspace {

	public function SetParameters($workspace_id,$user_id) {
		if (!IS_NUMERIC($workspace_id)) { $this->Errors("Invalid workspace"); return False; }
		if (!IS_NUMERIC($user_id)) { $this->Errors("Invalid user"); return False; }

		$this->workspace_id=$workspace_id;
	}

	public function Delete() {

		$db=$GLOBALS['db'];

		$sql="DELETE FROM ".$GLOBALS['database_prefix']."core_workspace_master
					WHERE workspace_id = '".$this->workspace_id."'
					";
		$result=$db->query($sql);
		if ($db->AffectedRows($result) > 0) {
			$log_result=LogHistory("Workspace ID ".$this->workspace_id." deleted");
			if ($log_result) { return True; } else { return False; }
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