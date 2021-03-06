<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class SchedulingLecturerTimetable {

	function __construct() {

		$this->errors="";
	}
	// not used
	private function SetParameters($category_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($category_id)) { $this->Errors("Invalid category"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->category_id=$category_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info($category_id);

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}
	// not used
	private function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT category_name
					FROM ".$GLOBALS['database_prefix']."helpdesk_category_master
					WHERE category_id = '".$this->category_id."'
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
	//not used
	public function Add($time_start,$time_end,$day_of_week,$reserve_type) {

		/* CHECKS */
		//if(!preg_match("([a-zA-Z0-9])",$category_name))  { $this->Errors("Invalid category name. Please use only alpha-numeric values!"); return False; }
		if (RowExists("scheduling_lecturer_reserved_slots","user_id",$_SESSION['user_id'],"AND time_start='".$time_start."' AND time_end = '".$time_end."' AND day_of_week = '".$day_of_week."' AND workspace_id = ".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Slot already blocked."); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."scheduling_lecturer_reserved_slots
					(user_id,time_start,time_end,day_of_week,workspace_id,teamspace_id,reserve_type)
					VALUES (
					".$_SESSION['user_id'].",
					'".$time_start."',
					'".$time_end."',
					'".$day_of_week."',
					".$GLOBALS['workspace_id'].",
					".$GLOBALS['teamspace_id'].",
					'".$reserve_type."'
					)";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				//LogHistory($category_name." added");
				return True;
		}
		else {
			return False;
		}
	}

	public function SlotIsReserved($p_user_id,$p_time_start,$p_time_end,$p_day_of_week,$reserve_type) {
		$db=$GLOBALS['db'];
		$sql="SELECT ssm.subject_name, rbm.dow, rbm.time_start, rbm.time_end, rm.resource_name
					FROM scheduling_subject_detail ssd, resource_booking_master rbm, scheduling_subject_master ssm, resource_master rm
					WHERE ssd.user_id = ".$p_user_id."
					AND ssd.subject_id = ssm.subject_id
					AND ssd.resource_booking_id = rbm.resource_booking_id
					AND ('".$p_time_start."' >= rbm.time_start AND '".$p_time_start."' < rbm.time_end)
					AND rbm.dow = '".$p_day_of_week."'
					AND rbm.resource_id = rm.resource_id
					";
		//echo $sql."<br>";

		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$this->slot_subject_name=$row['subject_name'];
				$this->slot_resource_name=$row['resource_name'];
				return True;
			}
		}
		else {
			return False;
		}
	}
	// not used
	public function Edit($category_name) {

		/* CHECKS */
		if (!$this->parameter_check) { $this->Errors("Parameter check failed"); return False; }
		if(!preg_match("([a-zA-Z0-9])",$category_name))  { $this->Errors("Invalid category name. Please use only alpha-numeric values!"); return False; }
		if (RowExists("helpdesk_category_master","category_name","'".$category_name."'","AND workspace_id=".$GLOBALS['workspace_id'])) { $this->Errors("Category name exists. Please choose another."); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="UPDATE ".$GLOBALS['database_prefix']."helpdesk_category_master
					SET category_name = '".$category_name."'
					WHERE category_id = ".$this->category_id."
					";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				return True;
		}
		else {
			return False;
		}
	}
	//not used
	public function Reset() {

		$db=$GLOBALS['db'];


		$sql="DELETE
					FROM ".$GLOBALS['database_prefix']."scheduling_lecturer_reserved_slots
					WHERE user_id = ".$_SESSION['user_id']."
					";
		//echo $sql;
		$result=$db->query($sql);
		if ($db->AffectedRows($result) > 0) {
			//LogHistory($this->category_name." deleted");
			return True;
		}
		else {
			$this->Errors("Failed to reset");
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