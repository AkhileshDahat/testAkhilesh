<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class DeleteGroup {

	function Remove($user_id,$group_id) {

		if (EMPTY($user_id)) { $this->Errors("Invalid User"); return False; }
		if (EMPTY($group_id)) { $this->Errors("Invalid Group"); return False; }

		$this->db=$GLOBALS['db'];

		$sql="DELETE FROM ".$GLOBALS['database_prefix']."group_master
					WHERE user_id = '".EscapeData($user_id)."'
					AND group_id = '".EscapeData($group_id)."'
					";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->Errors("Group not deleted.");
			return False;
		}
	}

	function Errors($err) {
		$this->errors.=$err."<br>";
	}

	function ShowErrors() {
		return $this->errors;
	}

}
?>