<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class NewMessage {

	public function SetParameters($workspace_id,$teamspace_id,$user_id_from,$message) {

		$this->workspace_id=$workspace_id;
		$this->teamspace_id=$teamspace_id;
		$this->user_id_from=$user_id_from;
		$this->message=EscapeData($message);

		if (EMPTY($this->workspace_id)) { $this->Errors("Please logon to a workspace before proceeding"); return False; }
		if (EMPTY($this->user_id_from)) { $this->Errors("User from is invalid"); return False; }
		if (EMPTY($this->message)) { $this->Errors("Messages cannot be empty"); return False; }

	}

	function SendMessage($user_id_to) {

		$this->db=$GLOBALS['db'];
		/* THIS IS A FUNCTION (STORED PROCEDURE) */
		$sql="SELECT ".$GLOBALS['database_prefix']."f_message_insert (
					".$this->workspace_id.",
					".$this->teamspace_id.",
					".$this->user_id_from.",
					".$user_id_to.",
					'".$this->message."'
					)";
		$result = $this->db->Query($sql);
		if ($this->db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->Errors("Message not sent to one recipient.");
			return False;
		}
	}

	public function SendMessageGroup($group_id) {
		$db=$GLOBALS['db'];

		$sql="SELECT user_id
					FROM ".$GLOBALS['database_prefix']."group_member_master gmm
					WHERE gmm.group_id = ".$group_id."
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$this->SendMessage($row['user_id']);
			}
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