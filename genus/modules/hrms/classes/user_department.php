<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class UserDepartment {

	function __construct() {
		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;
	}

	public function SetParameters($p_user_id,$p_department_id) {

		/* CHECKS */
		if (!IS_NUMERIC($p_user_id)) { $this->Errors("Invalid user id"); return False; }
		if (!IS_NUMERIC($p_department_id)) { $this->Errors("Invalid department id"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->p_user_id=$p_user_id;

		$this->p_department_id=$p_department_id;

		/* CALL THE INFORMATION METHOD */
		$this->Info();

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	private function Info() {
		$db=$GLOBALS['db'];
		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."hrms_user_department
					WHERE user_id = '".$this->p_user_id."'
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

	public function Add() {

		if (!$this->parameter_check) { $this->Errors("Parameter check failed"); return False; }

		$db=$GLOBALS['db'];

		/* REMOVE ANY PREVIOUS DATA */
		$sql="DELETE FROM ".$GLOBALS['database_prefix']."hrms_user_department
					WHERE user_id = ".$this->p_user_id
					;
		//echo $sql;
		$result = $db->Query($sql);

		/* INSERT DATA */
		$sql="INSERT INTO ".$GLOBALS['database_prefix']."hrms_user_department
					(user_id,department_id)
					VALUES (
					'".$this->p_user_id."',
					'".$this->p_department_id."'
					)";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->Errors("Operation failed to add reporting.");
			return False;
		}
	}

	public function GetInfo($v) {
		return $this->$v;
	}

	function Errors($err) {
		$this->errors.=$err."<br>";
	}

	function ShowErrors() {
		return $this->errors;
	}
}
?>