<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* A CLASS TO LOGIN THE USER */

class ActivateTeamspace {

	function SetCredentials($user_id,$teamspace_id) {
		/* SET THE VAR TO FAILED */
		$this->CredentialCheckOk=False;

		if (EMPTY($user_id)) { $this->Errors("Not logged in!"); return False; }
		if (EMPTY($teamspace_id)) { $this->Errors("Invalid teamspace selected!"); return False; }
		if (!IS_NUMERIC($teamspace_id)) { $this->Errors("Invalid teamspace. Non numeric value!"); return False; }
		if (!function_exists("RowExists")) { $this->Errors("teamspace activation failed!"); return False; }
		if (!RowExists("core_teamspace_master","teamspace_id",$teamspace_id,"")) { $this->Errors("Invalid teamspace. teamspace does not exist.!"); return False; }
		//if (!RowExists("teamspace_acl","teamspace_id",$teamspace_id,"AND role_id = '".$GLOBALS['wui']->RoleID()."'")) { $this->Errors("Invalid teamspace. Access denied.!"); return False; }

		$this->user_id=$user_id;
		$this->teamspace_id=$teamspace_id;
		/* OKAY ALL THE ABOVE WORKED */
		$this->CredentialCheckOk=True;
	}

	public function Activate() {
		if (!$this->CredentialCheckOk) { return False; }
		$db=$GLOBALS['db'];
		/* UPDATE THE USER TABLE */
		$sql="UPDATE ".$GLOBALS['database_prefix']."core_user_master
					SET teamspace_id = ".$this->teamspace_id."
					WHERE user_id = ".$this->user_id."
					";
		//echo $sql."<br>";
		$result=$db->Query($sql);
		if ($db->AffectedRows($result) == 0) {
			$this->Errors("Activating teamspace failed");
			return False;
		}
		else {
			return True;
		}
	}

	public function Deactivate() {

		$db=$GLOBALS['db'];
		/* UPDATE THE USER TABLE */
		$sql="UPDATE ".$GLOBALS['database_prefix']."core_user_master
					SET teamspace_id = NULL
					WHERE user_id = ".$_SESSION['user_id']."
					";
		$result=$db->Query($sql);
		if ($db->AffectedRows($result) == 0) {
			$this->Errors("Deactivating teamspace failed. Were you in a teamspace?");
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