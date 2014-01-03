<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/date_time/valid_date.php";

class ResourceID {

	function __construct() {

		$this->errors="";
	}
	public function SetParameters($resource_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($resource_id)) { $this->Errors("Invalid resource"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->resource_id=$resource_id;

		/* CALL THE periodID INFORMATION */
		$this->Info($resource_id);

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	private function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT resource_name,capacity
					FROM ".$GLOBALS['database_prefix']."resource_master
					WHERE resource_id = '".$this->resource_id."'
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
		else {
			return "";
		}
	}

	public function ResourceBookingIDAdd($resource_id,$date_from,$date_to,$dow,$time_start,$time_end,$description,$subject_group_id) {

		/* CHECKS */
		if(!ValidDate($date_from)) { $this->Errors("Invalid date from!"); return False; }
		if(!ValidDate($date_to)) { $this->Errors("Invalid date to!"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."resource_booking_master
					(resource_id,date_from,date_to,dow,time_start,time_end,user_id,description,subject_group_id)
					VALUES (
					'".$resource_id."',
					'".$date_from."',
					'".$date_to."',
					'".$dow."',
					'".$time_start."',
					'".$time_end."',
					".$_SESSION['user_id'].",
					'".$description."',
					'".$subject_group_id."'
					)";
		//echo $sql."<br />";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				//LogHistory($period_name." added");
				$this->last_insert_resource_booking_id=$db->LastInsertID();
				return True;
		}
		else {
			return False;
		}
	}
	/*
	*******************UNUSED *********************
	*/
	public function Edit($date_from,$date_to) {

		/* CHECKS */
		if(!ValidDate($date_from)) { $this->Errors("Invalid date from!"); return False; }
		if(!ValidDate($date_to)) { $this->Errors("Invalid date to!"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="UPDATE ".$GLOBALS['database_prefix']."leave_period_master
					SET date_from = '".$date_from."',
					date_to = '".$date_to."'
					WHERE resource_id = ".$this->resource_id."
					";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
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
						WHERE resource_id = ".$this->resource_id."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				//LogHistory($this->period_name." deleted");
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