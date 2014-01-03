<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* A CLASS TO LOGIN THE USER */

class Login {

	public function __construct() {
		$this->debug=False;
		//$this->debug=True; // comment out to disable debugging
		}

	public function SetParameters($username,$password,$remember_me="n") {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECK FOR TAINTED DATA */
		if (EMPTY($username)) { $this->Errors("Username is required to login!"); return False; }
		if (EMPTY($password)) { $this->Errors("Password is required to login!"); return False; }

		/* CHECK THAT THE REQUIRED FUNCTIONS EXIST */
		if (!function_exists("GetColumnValue")) { $this->Errors("Could not determine your user ID!"); return False; }
		if (!function_exists("EscapeData")) { $this->Errors("Could not login you in. Data cleansing failed!"); return False; }

		/* STORE THE VARIABLES LOCALLY */
		$this->username=EscapeData($username);
		$this->password=EscapeData($password);
		if ($remember_me!="n") {
			$this->SetRememberMeCookie();
		}

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		/* RETURN SUCCESS */
		return True;
	}

	public function Verify() {
		if ($GLOBALS['authentication_type'] == "mysql") {
			return $this->VerifyUserLocaDatabase();
		}
		elseif ($GLOBALS['authentication_type'] == "imap") {
			return $this->VerifyUserIMAP();
		}

	}

	private function VerifyUserLocaDatabase() {

		$db=$GLOBALS['db'];

		$sql="SELECT activated
					FROM ".$GLOBALS['database_prefix']."core_user_master
					WHERE login = '".$this->username."'
					AND password = MD5('".$this->password."')";
		//echo $sql;
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while ($row = $db->FetchArray($result)) {
				if ($row['activated'] == "y") {
					$this->GetUserID();
					//echo "Setting session<br />";
					$this->SetSession();
					$this->UpdateSessionID();
					return True;
				}
				else {
					$this->Errors("Your account has not been activated yet. Please check your email");
				}
			}
		}
		else {
			$this->Errors("Unable to login. Username / password is incorrect");
			return False;
		}
	}

	private function VerifyUserIMAP() {

		$mbox = imap_open("{".$GLOBALS['auth_imap_server']."}INBOX",$this->username,$this->password);
		if ($mbox) {
			$this->GetUserID();
			$this->SetSession();
			$this->UpdateSessionID();
			return True;
		}
		else {
			return False;
		}
	}

	private function GetUserID() {
		$this->user_id=GetColumnValue("user_id","core_user_master","login",$this->username);
	}

	private function SetSession() {
		/* REGISTER THE SESSION NAMES */
		session_register('sid');
		session_register('user_id');
		$sid="";
		$user_id="";
		/* SET VALUES FOR THE SESSION NAMES */
		//$sess_val=md5($this->user_id.microtime());
		//session_id($sess_val);
		//echo session_id();
		//echo "ok<br />";
		$_SESSION['sid']=session_id();

		$_SESSION['user_id']=$this->user_id;
		$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
	}

	private function UpdateSessionID() {
		$db=$GLOBALS['db'];
		$sql="UPDATE ".$GLOBALS['database_prefix']."core_user_master SET session_id = '".$_SESSION['sid']."', logged_in = 'y' WHERE login = '".$this->username."'";
		if ($this->debug) { $this->ShowDebug("Update user SQL",$sql); }
		$result=$db->query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			return False;
		}
	}

	public function SetRememberMeCookie() {
		$result=setcookie("mvh_username",$this->username,time()+60*60*24*999);
	}

	private function ShowDebug($desc,$data) {
		echo $desc."<br />";
		echo $data."<br />";
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}

}
?>