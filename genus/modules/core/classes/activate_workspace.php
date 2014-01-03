<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* A CLASS TO LOGIN THE USER */

class ActivateWorkspace {

	function SetCredentials($user_id,$workspace_id) {
		/* SET THE VAR TO FAILED */
		$this->CredentialCheckOk=False;

		if (EMPTY($user_id)) { $this->Errors("Not logged in!"); return False; }
		if (EMPTY($workspace_id)) { $this->Errors("Invalid workspace selected!"); return False; }
		if (!IS_NUMERIC($workspace_id)) { $this->Errors("Invalid workspace. Non numeric value!"); return False; }
		if (!function_exists("RowExists")) { $this->Errors("Workspace activation failed!"); return False; }
		if (!RowExists("core_workspace_master","workspace_id",$workspace_id,"")) { $this->Errors("Invalid workspace. Workspace does not exist.!"); return False; }
		if (!RowExists("core_space_users","user_id",$user_id,"AND workspace_id = '".$workspace_id."'")) { $this->Errors("Invalid workspace. Access denied.!"); return False; }
		if (!RowExists("core_space_users","user_id",$user_id,"AND workspace_id = '".$workspace_id."' AND approved = 'y'")) { $this->Errors("You are not approved. Wait for approval!"); return False; }

		$this->user_id=$user_id;
		$this->workspace_id=$workspace_id;
		/* OKAY ALL THE ABOVE WORKED */
		$this->CredentialCheckOk=True;
	}

	public function Activate() {
		if (!$this->CredentialCheckOk) { return False; }
		$db=$GLOBALS['db'];
		/* UPDATE THE USER TABLE */
		$sql="UPDATE ".$GLOBALS['database_prefix']."core_user_master
					SET workspace_id = ".$this->workspace_id."
					WHERE user_id = ".$this->user_id."
					";
		$result=$db->Query($sql);
		if ($db->AffectedRows($result) == 0) {
			$this->Errors("Activating workspace failed");
			return False;
		}
		else {
			return True;
		}
	}

	public function Deactivate($user_id) {

		$db=$GLOBALS['db'];
		/* UPDATE THE USER TABLE */
		$sql="UPDATE ".$GLOBALS['database_prefix']."core_user_master
					SET workspace_id = NULL, teamspace_id = NULL
					WHERE user_id = ".$user_id."
					";
		$result=$db->Query($sql);
		if ($db->AffectedRows($result) == 0) {
			$this->Errors("Deactivating workspace failed. Were you in a workspace?");
			return False;
		}
		else {
			return True;
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