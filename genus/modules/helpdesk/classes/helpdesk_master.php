<?php
class HelpdeskMaster {

	function __construct() {
		$this->db=$GLOBALS['db'];
		$this->Info();
	}

	function Info() {



		$sql="SELECT hm.master_id, hm.short_code, hm.ticket_count, hm.description, hm.logo,
					hm.use_approval, hm.use_attachments,
					hm.use_tags, hm.use_interested_parties, hm.use_custom_locations, hm.use_tasks, hm.use_custom_fields,
					hm.use_feedback, hm.use_review,
					hm.email_address_from, hm.email_color,
					hm.log_helpdesk_history, hm.log_ticket_history
					FROM ".$GLOBALS['database_prefix']."helpdesk_master hm
					WHERE hm.workspace_id = '".$GLOBALS['workspace_id']."'
					AND hm.teamspace_id '".$GLOBALS['teamspace_sql']."'";
		//echo $sql;
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

	public function CheckSetup() {
	}


	/* GET A COLUMN NAME FROM THE ARRAY */
	public function GetColVal($col_name) {
		return $this->$col_name;
	}

	/*
		CHECK THAT AT LEAST 1 CATEGORY EXISTS
	*/
	public function OneCategoryExists($virtual_id) {

		$sql="SELECT count(*) as total
					FROM $app_db.helpdesk_category_master
					WHERE virtual_id = '".$virtual_id."'
					";
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				return $row['total'];
			}
		}
		return 0;
	}

	/* CHECK THAT AT LEAST 1 LOCATION EXISTS */
	public function OneLocationExists($virtual_id) {

		if ($this->use_custom_locations == "n") {
			$virtual_id="0";
		}

		$sql="SELECT count(*) as total
					FROM $app_db.intranet_location_master
					WHERE helpdesk_virtual_id = '".$virtual_id."'
					";
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				return $row['total'];
			}
		}
		return 0;
	}

	/*
		CHECK THAT AT LEAST 1 LOCATION EXISTS
	*/
	function OnePriorityExists($virtual_id) {

		$sql="SELECT count(*) as total
					FROM $app_db.helpdesk_priority_master
					WHERE virtual_id = '".$virtual_id."'
					";
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				return $row['total'];
			}
		}
		return 0;
	}
}
?>

