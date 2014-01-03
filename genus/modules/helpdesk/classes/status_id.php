<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class StatusID {

	function __construct() {

		$this->errors="";
	}
	public function SetParameters($status_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($status_id)) { $this->Errors("Invalid status"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->status_id=$status_id;

		/* CALL THE STATUS ID INFORMATION */
		$this->Info($status_id);

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	private function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."helpdesk_status_master
					WHERE status_id = '".$this->status_id."'
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
			return False;
		}
	}

	public function Add($status_name,$is_new,$is_new_default,$is_pending_approval,$is_in_progress,$is_completed,$is_closed,$is_deleted) {

		/* CHECKS */
		if(!preg_match("([a-zA-Z0-9])",$status_name))  { $this->Errors("Invalid status name. Please use only alpha-numeric values!"); return False; }
		if (RowExists("helpdesk_status_master","status_name","'".$status_name."'","AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Status name exists. Please choose another."); return False; }
		if ($is_new=="y") { $is_new="y"; } else { $is_new="n"; }
		if ($is_new_default=="y") { $is_new_default="y"; } else { $is_new_default="n"; }
		if ($is_pending_approval=="y") { $is_pending_approval="y"; } else { $is_pending_approval="n"; }
		if ($is_in_progress=="y") { $is_in_progress="y"; } else { $is_in_progress="n"; }
		if ($is_completed=="y") { $is_completed="y"; } else { $is_completed="n"; }
		if ($is_closed=="y") { $is_closed="y"; } else { $is_closed="n"; }
		if ($is_deleted=="y") { $is_deleted="y"; } else { $is_deleted="n"; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."helpdesk_status_master
					(status_name,is_new,is_new_default,is_pending_approval,is_in_progress,is_completed,is_closed,is_deleted,workspace_id,teamspace_id)
					VALUES (
					'".$status_name."',
					'".$is_new."',
					'".$is_new_default."',
					'".$is_pending_approval."',
					'".$is_in_progress."',
					'".$is_completed."',
					'".$is_closed."',
					'".$is_deleted."',
					".$GLOBALS['workspace_id'].",
					".$GLOBALS['teamspace_id']."
					)";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				//LogHistory($status_name." added");
				return True;
		}
		else {
			return False;
		}
	}

	public function Edit($status_name,$is_new,$is_new_default,$is_pending_approval,$is_in_progress,$is_completed,$is_closed,$is_deleted) {

		/* CHECKS */
		if (!$this->parameter_check) { $this->Errors("Parameter check failed"); return False; }
		if(!preg_match("([a-zA-Z0-9])",$status_name))  { $this->Errors("Invalid status name. Please use only alpha-numeric values!"); return False; }
		//if (RowExists("helpdesk_status_master","status_name","'".$status_name."'","AND workspace_id=".$GLOBALS['workspace_id'])) { $this->Errors("Status name exists. Please choose another."); return False; }
		if ($is_new=="y") { $is_new="y"; } else { $is_new="n"; }
		if ($is_new_default=="y") { $is_new_default="y"; } else { $is_new_default="n"; }
		if ($is_pending_approval=="y") { $is_pending_approval="y"; } else { $is_pending_approval="n"; }
		if ($is_in_progress=="y") { $is_in_progress="y"; } else { $is_in_progress="n"; }
		if ($is_completed=="y") { $is_completed="y"; } else { $is_completed="n"; }
		if ($is_closed=="y") { $is_closed="y"; } else { $is_closed="n"; }
		if ($is_deleted=="y") { $is_deleted="y"; } else { $is_deleted="n"; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="UPDATE ".$GLOBALS['database_prefix']."helpdesk_status_master
					SET status_name = '".$status_name."',
					is_new = '".$is_new."',
					is_new_default = '".$is_new_default."',
					is_pending_approval = '".$is_pending_approval."',
					is_in_progress = '".$is_in_progress."',
					is_in_progress = '".$is_in_progress."',
					is_completed = '".$is_completed."',
					is_closed = '".$is_closed."',
					is_deleted = '".$is_deleted."'
					WHERE status_id = ".$this->status_id."
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
						FROM ".$GLOBALS['database_prefix']."helpdesk_status_master
						WHERE status_id = ".$this->status_id."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				//LogHistory($this->status_name." deleted");
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