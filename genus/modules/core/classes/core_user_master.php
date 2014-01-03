<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );


class CoreUserMaster {

	function __construct() {
		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;
	}

	public function SetParameters($user_id) {

		/* CHECKS */
		if (!IS_NUMERIC($user_id)) { $this->Errors("Invalid user id"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->user_id=$user_id;

		/* CALL THE INFORMATION METHOD */
		$this->Info();

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	private function Info() {
		$db=$GLOBALS['db'];
		$sql="SELECT login,password
					FROM ".$GLOBALS['database_prefix']."core_user_master
					WHERE user_id = '".$this->user_id."'
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
		else {
			return False;
		}
	}

	function ChangePassword($password,$password_repeat) {

		$db=$GLOBALS['db'];

		if ($this->parameter_check && $password==$password_repeat) {
			$sql="UPDATE ".$GLOBALS['database_prefix']."core_user_master
						SET password = MD5('".$password."')
						WHERE user_id = ".$this->user_id."
						";

			$result = $db->Query($sql);

			if ($db->AffectedRows($result) > 0) {
				return True;
			}
			else {
				$this->Errors("Failed to change password.");
				return False;
			}
		}
		else {
			$this->Errors("Failed to change password. Passwords equal?");
			return False;
		}
	}

	function GetUserID($login) {
		$db=$GLOBALS['db'];

		$sql="SELECT user_id
					FROM ".$GLOBALS['database_prefix']."core_user_master
					WHERE login = '".$login."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				return $row['user_id'];
			}
		}
		else {
			return False;
		}
	}

	public function ChangeDashboard($v) {
		if ($this->parameter_check) {
			if ($v=="y") { $v="y"; } else { $v="n"; }
			$db=$GLOBALS['db'];
			$sql="UPDATE ".$GLOBALS['database_prefix']."core_user_master
						SET show_dashboard = '".$v."'
						WHERE user_id = ".$this->user_id."
						";
			$result = $db->Query($sql);
			if ($result) {
				return True;
			}
			else {
				return False;
			}
		}
	}

	public function SetLanguage($lang) {
		if (!$this->parameter_check) { $this->Errors("Unable to change language 1"); return False; }
		$db=$GLOBALS['db'];
		$sql="UPDATE ".$GLOBALS['database_prefix']."core_user_master
					SET language = '".EscapeData($lang)."'
					WHERE user_id = ".$_SESSION['user_id']."
					";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->Errors("Unable to change language");
			return False;
		}
	}

	public function GetInfo($v) {
		if (ISSET($this->$v)) {
			return $this->$v;
		}
		else {
			return "";
		}
	}

	function Errors($err) {
		$this->errors.=$err."<br>";
	}

	function ShowErrors() {
		return $this->errors;
	}
}
?>