<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class AttachmentID {
	/* CONSTRUCTOR FUNCTION */
	function __construct() {
	/* SETTING PARAMETERS */
	}
	/* SET PARAMETERS */
	public function SetParameters($attachment_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($attachment_id)) { $this->Errors("Invalid Attachment"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->attachment_id=$attachment_id;

		/* CALL THE ATTACHMENT_ID INFORMATION - WE NEED THIS BEFORE RUNNING THE BELOW CHECK ON SECURITY*/
		$this->Info();

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}
	/* GET INFORMATION ABOUT THE ATTACHMENT ID */
	private function Info() {

		$db=$GLOBALS['db'];

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."helpdesk_ticket_attachments
					WHERE attachment_id = '".$this->attachment_id."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);

		if ($db->NumRows($result) > 0) {
			while($row=$db->FetchArray($result)) {
				/* HERE WE CALL THE FIELDS AND SET THEM INTO DYNAMIC VARIABLES */
				$arr_cols=$db->GetColumns($result);
				for ($i=1;$i<count($arr_cols);$i++) {
					$col_name=$arr_cols[$i];
					$this->$col_name=$row[$col_name];
				}
			}
		}
	}
	/* THIS IS USED IN DOWNLOAD PAGES - BECAUSE WE RETURN THE ATTACHMENT WHICH CAN BE HUGE */
	public function TicketAttachment() {

		if ($this->parameter_check) {

			$db=$GLOBALS['db'];

			$sql="SELECT attachment
						FROM ".$GLOBALS['database_prefix']."helpdesk_ticket_attachments
						WHERE attachment_id = '".$this->attachment_id."'
						";
			//echo $sql."<br>";
			$result = $db->Query($sql);
			if ($db->NumRows($result) > 0) {
				while($row = $db->FetchArray($result)) {
					if ($GLOBALS['database_type'] == "postgres") {
						return pg_unescape_bytea($row["attachment"]);
					}
					else {
						return $row["attachment"];
					}
				}
			}
		}
	}
	/* GET A COLUMN NAME FROM THE ARRAY */
	function GetColVal($col_name) {
		return $this->$col_name;
	}

	/* DELETE THE ENTIRE FILE AND ALL OTHER VERSIONS */
	public function DeleteFile() {

		if ($this->parameter_check) {

			/* DATABASE CONNECTION */
			$db=$GLOBALS['db'];

			$sql="DELETE FROM ".$GLOBALS['database_prefix']."helpdesk_ticket_attachments
						WHERE attachment_id = '".$this->attachment_id."'
						";
			$result=$db->Query($sql);
			if ($db->AffectedRows($result) > 0) {
				return True;
			}
			else {
				return False;
			}
		}
		else {
			$this->Errors("Parameter check failed");
			return False;
		}
	}

	/* ERRORS FUNCTION */
	private function Errors($err) {
		$this->errors.=$err."<br>";
	}
	/* PUBLIC FUNCTION TO SHOW ERRORS */
	public function ShowErrors() {
		return $this->errors;
	}
}
?>