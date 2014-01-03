<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class PublicHoliday {

	function __construct() {

		$this->errors="";
	}
	public function SetParameters($date_pub_hol) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!preg_match("/^\d\d\d\d-\d\d-\d\d$/",$date_pub_hol)) { $this->Errors("Invalid date 1"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->date_pub_hol=$date_pub_hol;

		/* CALL THE departmentID INFORMATION */
		$this->Info($date_pub_hol);

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	private function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."hrms_public_holiday_master
					WHERE date_pub_hol = '".$this->date_pub_hol."'
					";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				/* HERE WE CALL THE FIELDS AND SET THEM INTO DYNAMIC VARIABLES */
				$arr_cols=$this->db->GetColumns($result);
				for ($i=1;$i<count($arr_cols);$i++) {
					$col_name=$arr_cols[$i];
					$this->$col_name=$row[$col_name];
				}
			}
		}
	}

	public function GetInfo($v) {
		return $this->$v;
	}

	public function Add($date_pub_hol) {

		/* CHECKS */
		if (!preg_match("/^\d\d\d\d-\d\d-\d\d$/",$date_pub_hol)) { $this->Errors("Invalid date 2"); return False; }
		if (RowExists("hrms_public_holiday_master","date_pub_hol","'".$date_pub_hol."'","AND workspace_id=".$GLOBALS['workspace_id'])) { $this->Errors("Date exists. Please choose another."); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."hrms_public_holiday_master
					(date_pub_hol,workspace_id)
					VALUES (
					'".$date_pub_hol."',
					".$GLOBALS['workspace_id']."
					)";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				//LogHistory($department_name." added");
				return True;
		}
		else {
			return False;
		}
	}

	public function Delete() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			$sql="DELETE
						FROM ".$GLOBALS['database_prefix']."hrms_public_holiday_master
						WHERE date_pub_hol = '".$this->date_pub_hol."'
						";
			echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				//LogHistory($this->department_name." deleted");
				return True;
			}
			else {
				$this->Errors("Failed to delete");
				return False;
			}
		}
		else {
			$this->Errors("Sorry, parameter check failed<br>");
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