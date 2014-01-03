<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class Setup {

	public function SetParameters($workspace_id,$teamspace_id) {
	}

	public function HelpdeskSetupOk() {

		if (!$this->OneCategoryExists()) { return False; }
		if (!$this->OneLocationExists()) { return False; }
		if (!$this->OnePriorityExists()) { return False; }
	}
	/* CHECK THAT AT LEAST 1 CATEGORY EXISTS */
	private function OneCategoryExists() {

		$sql="SELECT count(*) as total
					FROM $app_db.helpdesk_category_master
					WHERE master_id = '".$master_id."'
					";
		$result = $this->db->query($sql);
		if ($this->db->num_rows($result) > 0) {
			while($row = $this->db->fetch_array($result)) {
				return $row['total'];
			}
		}
		return 0;
	}

	/*
		CHECK THAT AT LEAST 1 LOCATION EXISTS
	*/
	function OneLocationExists($master_id) {

		if ($this->enable_custom_locations == "n") {
			$master_id="0";
		}

		$sql="SELECT count(*) as total
					FROM $app_db.intranet_location_master
					WHERE helpdesk_master_id = '".$master_id."'
					";
		$result = $this->db->query($sql);
		if ($this->db->num_rows($result) > 0) {
			while($row = $this->db->fetch_array($result)) {
				return $row['total'];
			}
		}
		return 0;
	}

	/*
		CHECK THAT AT LEAST 1 LOCATION EXISTS
	*/
	function OnePriorityExists($master_id) {

		$sql="SELECT count(*) as total
					FROM $app_db.helpdesk_priority_master
					WHERE master_id = '".$master_id."'
					";
		$result = $this->db->query($sql);
		if ($this->db->num_rows($result) > 0) {
			while($row = $this->db->fetch_array($result)) {
				return $row['total'];
			}
		}
		return 0;
	}
}
?>

