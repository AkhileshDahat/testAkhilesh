<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/date_time/valid_date.php";
require_once $GLOBALS['dr']."include/functions/db/get_sequence_currval.php";

class BookingMaster {

	/* CONSTRUCTOR */
	function __construct($booking_id="",$facility_id,$date_from,$date_to,$user_id,$description) {

		/* SET THIS TO FALSE IN CASE WE HIT ANY ERRORS HERE OTHER PUBLIC FUNCTIONS SHOULD NOT RUN */
		$this->credentials_check=False;

		/* ERROR CHECKING */
		if (!EMPTY($booking_id) && !IS_NUMERIC($booking_id)) { $this->Errors("Invalid booking ID"); return False; }
		if (!IS_NUMERIC($facility_id)) { $this->Errors("Invalid facility ID"); return False; }
		if (!ValidDate($date_from)) { $this->Errors("Date from is invalid"); return False; }
		if (!ValidDate($date_to)) { $this->Errors("Date to is invalid"); return False; }

		/* CREATE LOCAL COPIES OF THE PARAMETERS */
		$this->booking_id=$booking_id;
		$this->facility_id=$facility_id;
		$this->date_from=$date_from;
		$this->date_to=$date_to;
		$this->user_id=$user_id;
		$this->description=$description;

		$this->status_id=1;

		if (!$this->FacilityAvailable($facility_id,$date_from,$date_to)) { $this->Errors("Facility not available"); return False; }

		/* NO PROBLEMS */
		$this->credentials_check=True;

	}

	/* ADD BOOKING */
	public function AddBooking() {

		if (!$this->credentials_check) { return False; }

		$db=$GLOBALS['db'];
		$sql="INSERT INTO ".$GLOBALS['database_prefix']."facility_booking_master
					(facility_id,status_id,date_from,date_to,user_id,description,workspace_id,teamspace_id)
					VALUES (
					".$this->facility_id.",
					".$this->status_id.",
					'".$this->date_from."',
					'".$this->date_to."',
					".$this->user_id.",
					'".$this->description."',
					".$GLOBALS['ui']->WorkspaceID().",
					".$GLOBALS['teamspace_id']."
					)
					";
		//echo $sql;
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			return False;
		}
	}

	public function FacilityAvailable($facility_id,$date_from,$date_to) {
		$db=$GLOBALS['db'];

	 	$sql="SELECT 'x'
	 	      FROM ".$GLOBALS['database_prefix']."facility_booking_master fbm,".$GLOBALS['database_prefix']."facility_master fm
	 	      WHERE fbm.facility_id = ".$facility_id."
	 	      AND (fbm.date_from = '".$date_from."' OR fbm.date_to = '".$date_to."')
	 	      OR (fbm.date_from >= '".$date_from."' AND fbm.date_to <= '".$date_to."')
	 	      OR (fbm.date_from >= '".$date_from."' AND fbm.date_from < '".$date_to."')
	 	      OR (fbm.date_to > '".$date_from."' AND fbm.date_to < '".$date_to."')
	 	      AND fbm.facility_id = fm.facility_id
	 	      AND fm.workspace_id = ".$GLOBALS['ui']->WorkspaceID()."
	 	      AND fm.teamspace_id	".$GLOBALS['teamspace_sql']."
	 	      ";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			echo "Booked";
			return False;
		}
		return True;
	}

	function DefaultStatus($v) {
		$db=$GLOBALS['db'];

	 	$sql="SELECT status_id
	 	      FROM ".$GLOBALS['database_prefix']."facility_status_master
	 	      WHERE $v = 'y'
	 	      ";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row=$db->FetchArray($result)) {
				return $row['status_id'];
			}
		}
		return False;
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}

}
?>