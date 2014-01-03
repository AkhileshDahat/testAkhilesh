<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class NewTicket {

	function AddTicket($virtual_id,$category_id,$priority_id,$location_id,$status_id,$user_id_logging,$user_id_problem,
										$title,$description,$technical_description,$solution,$technical_solution,$date_submit,
										$significance_id,$estimate_time_spent,$date_due) {

		if (EMPTY($virtual_id)) { $this->Errors("User Invalid"); return False; }
		if (EMPTY($workspace_id)) { $this->Errors("You must first enter a workspace"); return False; }
		if (EMPTY($group_name)) { $this->Errors("Group name can't be empty"); return False; }

		if (GroupNameExists($user_id,$workspace_id,$teamspace_id,$group_name)) { $this->Errors("You already have a group by that name"); return False; }

		$this->db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."group_master
					(user_id,workspace_id,teamspace_id,group_name)
					VALUES (
					".$user_id.",
					".$workspace_id.",
					".$teamspace_id.",
					'".EscapeData($group_name)."'
					)";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->Errors("Group creation failed. This error has been logged. Apologies for the inconvenience.");
			return False;
		}
	}

	function Errors($errno,$err) {
		$this->errors.=$errno.":".$err."<br>";
	}

	function ShowErrors() {
		return $this->errors;
	}

}
?>