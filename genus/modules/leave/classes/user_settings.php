<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class UserSettings {

	function __construct() {

		$this->errors="";
		$this->Info();

	}

	private function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT lus.period_id,lpm.date_from,lpm.date_to
					FROM ".$GLOBALS['database_prefix']."leave_user_settings lus,".$GLOBALS['database_prefix']."leave_period_master lpm
					WHERE lus.user_id = '".$_SESSION['user_id']."'
					AND lus.period_id = lpm.period_id
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
		/* NO RECORD IN THE TABLE */
		else {
			$sql="REPLACE INTO ".$GLOBALS['database_prefix']."leave_user_settings (user_id) VALUES ('".$_SESSION['user_id']."')";
			$result = $this->db->Query($sql);
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

	public function Refresh() {
		return $this->Info();
	}

	public function Edit($period_id) {

		/* CHECKS */
		if(!is_numeric($period_id)) { $this->Errors("Invalid period!"); return False; }
		if (!RowExists("leave_period_master","period_id",$period_id,"AND workspace_id ='".$GLOBALS['workspace_id']."'")) { $this->Errors("Invalid period [1]."); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="UPDATE ".$GLOBALS['database_prefix']."leave_user_settings
					SET period_id = '".$period_id."'
					WHERE user_id = ".$_SESSION['user_id']."
					";
		//echo $sql;
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				//LogHistory($period_name." added");
				return True;
		}
		else {
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