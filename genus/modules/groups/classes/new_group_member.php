<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class NewGroupMember {

	function AddGroupMember($group_id,$user_id) {

		if (EMPTY($group_id)) { $this->Errors("Group Invalid"); return False; }
		if (EMPTY($user_id)) { $this->Errors("User Invalid"); return False; }

		if (!is_numeric($group_id)) { $this->Errors("Group Invalid. Integer Error."); return False; }
		if (!is_numeric($user_id)) { $this->Errors("Group Invalid. Integer Error."); return False; }

		$this->db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."group_member_master
					(group_id,user_id)
					VALUES (
					".$group_id.",
					".$user_id."
					)";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->Errors("Group member creation failed. This error has been logged. Apologies for the inconvenience.");
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