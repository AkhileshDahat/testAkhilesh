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

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."sstars_user_settings sus
					WHERE sus.user_id = '".$_SESSION['user_id']."'
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
			$sql="REPLACE INTO ".$GLOBALS['database_prefix']."sstars_user_settings (user_id) VALUES ('".$_SESSION['user_id']."')";
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

	public function Edit() {


	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}
}
?>