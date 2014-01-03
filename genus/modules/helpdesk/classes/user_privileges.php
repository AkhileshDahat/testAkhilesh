<?php
class UserPrivileges {

	function UserPrivileges($user_id) {
		$this->db=$GLOBALS['db'];

		$sql="SELECT user_login_id, master_id, administrator, allow_review, allow_delegate,
					browse_show_importance, browse_show_significance, browse_show_status_img,
					browse_show_ticket_number, browse_show_user_problem, browse_show_status_text,
					browse_show_submit_date,
					browse_show_outstanding, browse_show_delegated_to, browse_show_adv_menu,
					browse_show_summary_popup, browse_show_category, browse_show_sub_category,
					browse_show_due_date_days,
					popup_command, ticket_location_id
					FROM ".$GLOBALS['database_prefix']."helpdesk_user_master
					WHERE user_login_id = '".$user_id."'
					";
		//echo $sql;
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				$this->valid_user = True;
				$this->user_login_id = $row['user_login_id'];
				$this->master_id = $row['master_id'];
				$this->administrator = $row['administrator'];
				$this->allow_review = $row['allow_review'];
				$this->allow_delegate = $row['allow_delegate'];

				$this->browse_show_importance = $row['browse_show_importance'];
				$this->browse_show_significance = $row['browse_show_significance'];
				$this->browse_show_status_img = $row['browse_show_status_img'];
				$this->browse_show_ticket_number = $row['browse_show_ticket_number'];
				$this->browse_show_user_problem = $row['browse_show_user_problem'];
				$this->browse_show_status_text = $row['browse_show_status_text'];
				$this->browse_show_outstanding = $row['browse_show_outstanding'];
				$this->browse_show_submit_date = $row['browse_show_submit_date'];
				$this->browse_show_delegated_to = $row['browse_show_delegated_to'];
				$this->browse_show_adv_menu = $row['browse_show_adv_menu'];
				$this->browse_show_summary_popup = $row['browse_show_summary_popup'];
				$this->browse_show_category = $row['browse_show_category'];
				$this->browse_show_sub_category = $row['browse_show_sub_category'];
				$this->browse_show_due_date_days = $row['browse_show_due_date_days'];

				$this->popup_command = $row['popup_command'];
				$this->ticket_location_id = $row['ticket_location_id'];
			}
		}
	}

	function ValidUser() { return $this->valid_user; }
	function MasterID() { return $this->master_id; }
	function IsAdministrator() { return $this->administrator; }
	function AllowReview() { return $this->allow_review; }
	function AllowDelegate() { return $this->allow_delegate; }

	function BrowseShowImportance() { return $this->browse_show_importance; }
	function BrowseShowSignificance() { return $this->browse_show_significance; }
	function BrowseShowStatusImg() { return $this->browse_show_status_img; }
	function BrowseShowTicketNumber() { return $this->browse_show_ticket_number; }
	function BrowseShowUserProb() { return $this->browse_show_user_problem; }
	function BrowseShowStatusText() { return $this->browse_show_status_text; }
	function BrowseShowSubmitDate() { return $this->browse_show_submit_date; }
	function BrowseShowOutstanding() { return $this->browse_show_outstanding; }
	function BrowseShowDelegatedTo() { return $this->browse_show_delegated_to; }
	function BrowseShowAdvMenu() { return $this->browse_show_adv_menu; }
	function BrowseShowSummaryPopup() { return $this->browse_show_summary_popup; }
	function BrowseShowCategory() { return $this->browse_show_category; }
	function BrowseShowSubCategory() { return $this->browse_show_sub_category; }
	function BrowseShowDueDateDays() { return $this->browse_show_due_date_days; }

	function PopupCommand() { return $this->popup_command; }
	function TicketLocationID() { return $this->ticket_location_id; }

	/*
		STORE THE HELPDESK MASTER ID IN THE SETTINGS TABLE
	*/
	function HelpdeskMasterID() {

		$sql="SELECT helpdesk_master_id
					FROM $app_db.helpdesk_user_master_settings
					WHERE user_login_id = '".$user_login_id."'
					";
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				return $row['helpdesk_master_id'];
			}
		}
	}
}
?>