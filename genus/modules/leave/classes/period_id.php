<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/date_time/valid_date.php";

class PeriodID {

	function __construct() {

		$this->errors="";
	}
	public function SetParameters($period_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($period_id)) { $this->Errors("Invalid period"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->period_id=$period_id;

		/* CALL THE periodID INFORMATION */
		$this->Info($period_id);

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	private function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT date_from,date_to,active
					FROM ".$GLOBALS['database_prefix']."leave_period_master
					WHERE period_id = '".$this->period_id."'
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
		if (ISSET($this->$v)) {
			return $this->$v;
		}
	}

	public function Add($date_from,$date_to) {

		/* CHECKS */
		if(!ValidDate($date_from)) { $this->Errors("Invalid date from!"); return False; }
		if(!ValidDate($date_to)) { $this->Errors("Invalid date to!"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."leave_period_master
					(date_from,date_to,workspace_id)
					VALUES (
					'".$date_from."',
					'".$date_to."',
					".$GLOBALS['workspace_id']."
					)";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				LogHistory("$date_from to $date_to added");
				return True;
		}
		else {
			return False;
		}
	}

	public function Edit($date_from,$date_to) {

		/* CHECKS */
		if(!ValidDate($date_from)) { $this->Errors("Invalid date from!"); return False; }
		if(!ValidDate($date_to)) { $this->Errors("Invalid date to!"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="UPDATE ".$GLOBALS['database_prefix']."leave_period_master
					SET date_from = '".$date_from."',
					date_to = '".$date_to."'
					WHERE period_id = ".$this->period_id."
					";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				LogHistory("$date_from to $date_to edited");
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
						FROM ".$GLOBALS['database_prefix']."leave_period_master
						WHERE period_id = ".$this->period_id."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				LogHistory($this->date_from." to ".$this->date_to." deleted");
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

	public function ChangeActiveStatus() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			if ($this->GetInfo("active") == "y") {
				$active = "n";
			}
			else {
				$active = "y";
			}
			$sql="UPDATE ".$GLOBALS['database_prefix']."leave_period_master
						SET active = '$active'
						WHERE period_id = ".$this->period_id."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				//LogHistory($this->period_name." deleted");
				// UPDATE ALL USERS TO NULL WHO HAVE THIS AS A SETTING
				$sql = "UPDATE ".$GLOBALS['database_prefix']."leave_user_settings
								SET period_id = NULL
								WHERE period_id = '".$this->period_id."'
								";
				$result=$db->query($sql);
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