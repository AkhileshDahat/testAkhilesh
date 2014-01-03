<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."include/functions/db/row_exists.php";

class NewUser {

	function AddUser($user_id,$username,$password,$full_name,$superior_user_id) {

		$this->db=$GLOBALS['db'];

		if (EMPTY($user_id) || !IS_NUMERIC($user_id)) { $this->Errors("User ID error"); return False; }
		if (EMPTY($username)) { $this->Errors("Missing username"); return False; }
		if (EMPTY($password)) { $this->Errors("Missing password"); return False; }
		if (EMPTY($full_name)) { $this->Errors("Missing fullname"); return False; }
		//if (EMPTY($department_id) || !IS_NUMERIC($department_id)) { $this->Errors("Department ID error"); return False; }
		if (EMPTY($superior_user_id) || !IS_NUMERIC($superior_user_id)) { $this->Errors("Superior ID error"); return False; }

		if (RowExists("core_user_master","user_id",$user_id,"")) { $this->Errors("User ID exists"); return False; }
		if (!RowExists("department_master","department_id",$department_id,"")) { $this->Errors("Department does not exist"); return False; }

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_user_master
					(user_id,username,password,full_name,superior_user_id)
					VALUES (
					'".$user_id."',
					'".$username."',
					MD5('".$password."'),
					'".$full_name."',
					'".$superior_user_id."'
					)";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->BatchErrors("Bug");
			return False;
		}
	}

	function Errors($err) {
		$this->batch_errors.=$err."<br>";
	}

	function ShowErrors() {
		return $this->batch_errors;
	}
}
?>