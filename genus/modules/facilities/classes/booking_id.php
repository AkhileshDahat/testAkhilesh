<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class BookingID {

	/* CONSTRUCTOR */
	function __construct($booking_id) {
		$db=$GLOBALS['db'];
		if (!IS_NUMERIC($booking_id)) { $this->Errors("Non numeric booking ID"); return False; }

		$sql="SELECT bm.status_id,um.full_name
					FROM ".$GLOBALS['database_prefix']."facility_booking_master bm, ".$GLOBALS['database_prefix']."core_user_master um
					WHERE bm.booking_id = ".$booking_id."
					AND bm.user_id = um.user_id
					";
		//echo $sql;
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$this->status_id=$row['status_id'];
				$this->full_name=$row['full_name'];
			}
		}
	}

	public function GetInfo($v) {
		return $this->$v;
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}

}
?>