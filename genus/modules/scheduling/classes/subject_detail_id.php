<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class SubjectDetailID {

	function __construct() {

		$this->errors="";
	}
	public function SetParameters($subject_detail_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($subject_detail_id)) { $this->Errors("Invalid subject detail"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->subject_detail_id=$subject_detail_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info($subject_detail_id);

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	private function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT ssd.subject_detail_id, ssm.subject_name, um.full_name, ssd.duration_hours, ssd.date_start, ssd.date_end, ssd.capacity, ssd.description
							FROM ".$GLOBALS['database_prefix']."core_user_master um, ".$GLOBALS['database_prefix']."scheduling_subject_master ssm, ".$GLOBALS['database_prefix']."scheduling_subject_detail ssd
							WHERE ssd.subject_detail_id = '".$this->subject_detail_id."'
							AND ssd.subject_id = ssm.subject_id
							AND ssd.user_id = um.user_id
							AND ssm.workspace_id = ".$GLOBALS['workspace_id']."
							AND ssm.teamspace_id ".$GLOBALS['teamspace_sql']."
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

	public function GetItemReqsArr() {

		if (!$this->parameter_check) { $this->Errors("Parameter check failed"); return False; }

		$arr=array();

		$this->db=$GLOBALS['db'];

		$sql="SELECT rim.item_id, rim.item_name
					FROM ".$GLOBALS['database_prefix']."scheduling_subject_detail_item_reqs sdir, ".$GLOBALS['database_prefix']."resource_item_master rim
					WHERE sdir.subject_detail_id = '".$this->subject_detail_id."'
					AND sdir.item_id = rim.item_id
					";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				$item_id=$row['item_id'];
				$arr[$item_id]=$row['item_name'];
			}
		}
		return $arr;
	}

	public function GetInfo($v) {
		return $this->$v;
	}

	public function Add($subject_id,$user_id,$duration_hours,$date_start,$date_end,$capacity,$description,$resource_item_reqs) {

		/* CHECKS */
		//if(!preg_match("([a-zA-Z0-9])",$subject_name))  { $this->Errors("Invalid subject name. Please use only alpha-numeric values!"); return False; }
		//if (RowExists("scheduling_subject_detail","subject_name","'".$subject_name."'","AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Category name exists. Please choose another."); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."scheduling_subject_detail
					(subject_id,user_id,duration_hours,date_start,date_end,capacity,description,workspace_id,teamspace_id,resource_item_reqs)
					VALUES (
					'".$subject_id."',
					'".$user_id."',
					'".$duration_hours."',
					'".$date_start."',
					'".$date_end."',
					'".$capacity."',
					'".$description."',
					".$GLOBALS['workspace_id'].",
					".$GLOBALS['teamspace_id'].",
					'".$resource_item_reqs."'
					)";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				//LogHistory($subject_name." added");
				$this->subject_detail_id=$db->LastInsertID();
				return True;
		}
		else {
			return False;
		}
	}

	public function InsertItemReqs($arr) {

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		foreach ($arr as $a) {
			$sql="INSERT INTO ".$GLOBALS['database_prefix']."scheduling_subject_detail_item_reqs
						(subject_detail_id,item_id)
						VALUES (
						'".$this->subject_detail_id."',
						'".mysql_escape_string($a)."'
						)";

			$result=$db->query($sql);
		}
	}

	public function Edit($subject_name) {

		/* CHECKS */
		if (!$this->parameter_check) { $this->Errors("Parameter check failed"); return False; }
		if(!preg_match("([a-zA-Z0-9])",$subject_name))  { $this->Errors("Invalid subject name. Please use only alpha-numeric values!"); return False; }
		if (RowExists("scheduling_subject_detail","subject_name","'".$subject_name."'","AND workspace_id=".$GLOBALS['workspace_id'])) { $this->Errors("Category name exists. Please choose another."); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="UPDATE ".$GLOBALS['database_prefix']."scheduling_subject_detail
					SET subject_name = '".$subject_name."'
					WHERE subject_detail_id = ".$this->subject_detail_id."
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
						FROM ".$GLOBALS['database_prefix']."scheduling_subject_detail
						WHERE subject_detail_id = ".$this->subject_detail_id."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				//LogHistory($this->subject_name." deleted");
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