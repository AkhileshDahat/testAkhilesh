<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* A CLASS TO LOGIN THE USER */

class Logout {

	function SetCredentials($user_id) {
		if (EMPTY($user_id)) { $this->Errors("Not logged in!"); return False; }
		$this->user_id=$user_id;
	}

	public function ExecuteLogout() {
		/* UPDATE THE USER TABLE */
		$db=$GLOBALS['db'];
		$this->DeleteSession();
		$sql="UPDATE ".$GLOBALS['database_prefix']."core_user_master SET logged_in = 'n' WHERE user_id = '".$this->user_id."'";
		$result=$db->query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			return False;
		}
	}

	private function DeleteSession() {
		//session_start();
		//$_SESSION = array();
		//session_destroy();
		//$_SESSION['user_id']="";
		/*session_register('sid');
		session_register('user_id');
		$sid="";
		$user_id="";
		$_SESSION['user_id']="22";
		*/
		unset($_SESSION['sid']);
		unset($_SESSION['user_id']);
		session_destroy();
		session_regenerate_id();
		//echo "ok123";
		//echo $_SESSION['user_id'];
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}

}
?>