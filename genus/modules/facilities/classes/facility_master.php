<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/date_time/valid_time.php";
require_once $GLOBALS['dr']."include/functions/db/get_sequence_currval.php";

class FacilityMaster {

	public function Info($facility_id) {
		$db=$GLOBALS['db'];
		if (!IS_NUMERIC($facility_id)) { $this->Errors("Non numeric facility ID"); return False; }

		$sql="SELECT facility_name,description,logo,max_user_bookings_week,booking_interval
					FROM ".$GLOBALS['database_prefix']."facility_master
					WHERE facility_id = ".$facility_id."
					";
		//echo $sql;
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$this->facility_name=$row['facility_name'];
				$this->description=$row['description'];
				$this->logo=$row['logo'];
				$this->max_user_bookings_week=$row['max_user_bookings_week'];
				$this->booking_interval=$row['booking_interval'];
			}
		}
	}

	public function InfoTimes($facility_id) {
		$db=$GLOBALS['db'];
		if (!IS_NUMERIC($facility_id)) { $this->Errors("Non numeric facility ID"); return False; }

		$sql="SELECT mon_from,mon_to,tue_from,tue_to,wed_from,wed_to,thur_from,thur_to,fri_from,fri_to,sat_from,sat_to,sun_from,sun_to
					FROM ".$GLOBALS['database_prefix']."facility_hours
					WHERE facility_id = ".$facility_id."
					";
		//echo $sql;
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$this->mon_from=$row['mon_from'];
				$this->mon_to=$row['mon_to'];
				$this->tue_from=$row['tue_from'];
				$this->tue_to=$row['tue_to'];
				$this->wed_from=$row['wed_from'];
				$this->wed_to=$row['wed_to'];
				$this->thur_from=$row['thur_from'];
				$this->thur_to=$row['thur_to'];
				$this->fri_from=$row['fri_from'];
				$this->fri_to=$row['fri_to'];
				$this->sat_from=$row['sat_from'];
				$this->sat_to=$row['sat_to'];
				$this->sun_from=$row['sun_from'];
				$this->sun_to=$row['sun_to'];
			}
		}
	}

	public function GetInfo($v) {
		return $this->$v;
	}

	public function Add($workspace_id,$teamspace_id,$facility_name,$description,$logo,$max_user_bookings_week) {
		$db=$GLOBALS['db'];

		if (EMPTY($facility_name)) { $this->Errors("Facility Name Required"); return False; }
		if (EMPTY($description)) { $this->Errors("Description required"); return False; }
		if (EMPTY($logo)) { $this->Errors("Logo Required"); return False; }
		if (IS_NUMERIC($max_user_bookings_week)) { $max_user_bookings_week=$max_user_bookings_week; } else { $max_user_bookings_week="NULL"; }

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."facility_master
					(workspace_id,teamspace_id,facility_name,description,logo,max_user_bookings_week)
					VALUES (
					".$workspace_id.",
					".NullCheck($teamspace_id).",
					'".EscapeData($facility_name)."',
					'".EscapeData($description)."',
					'".EscapeData($logo)."',
					".$max_user_bookings_week."
					)";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			$this->facility_id=$db->LastInsertID();
			$sql="INSERT INTO ".$GLOBALS['database_prefix']."facility_hours (facility_id) VALUES (".$this->facility_id.")";
			$result = $db->Query($sql);
			if ($db->AffectedRows($result) > 0) {
				return True;
			}
			else {
				$this->Errors("Bug in creating Facility Times.");
			}
		}
		else {
			$this->Errors("Bug in creating Facility.");
			return False;
		}
	}

	public function Edit($facility_id,$facility_name,$description,$logo,$max_user_bookings_week) {

		$db=$GLOBALS['db'];
		if (!IS_NUMERIC($facility_id)) { $this->Errors("Non numeric Facility ID"); return False; }
		if (EMPTY($facility_name)) { $this->Errors("Facility Name Required"); return False; }
		if (EMPTY($description)) { $this->Errors("Description required"); return False; }
		if (EMPTY($logo)) { $this->Errors("Logo Required"); return False; }
		if ($max_user_bookings_week=="y") { $max_user_bookings_week="y"; } else { $max_user_bookings_week="n"; }

		$sql="UPDATE ".$GLOBALS['database_prefix']."facility_master SET
					facility_name = '".EscapeData($facility_name)."',
					description = '".EscapeData($description)."',
					logo = '".EscapeData($logo)."',
					max_user_bookings_week = '".$max_user_bookings_week."'
					WHERE facility_id = $facility_id";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->Errors("Bug in editing error.");
			return False;
		}
	}

	public function Delete($user_id,$facility_id) {

		if (EMPTY($user_id)) { $this->Errors("Invalid User"); return False; }
		if (!IS_NUMERIC($facility_id)) { $this->Errors("Non Numeric Error ID"); return False; }

		$this->db=$GLOBALS['db'];

		$sql="DELETE FROM ".$GLOBALS['database_prefix']."facility_master
					WHERE facility_id = ".$facility_id."
					";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->Errors("Error not deleted.");
			return False;
		}
	}

	public function Times($facility_id,$mon_from,$mon_to,$tue_from,$tue_to,$wed_from,$wed_to,$thur_from,$thur_to,$fri_from,$fri_to,$sat_from,$sat_to,$sun_from,$sun_to) {

		if (!IS_NUMERIC($facility_id)) { $this->Errors("Invalid facility ID"); return False; }

		/* THIS IS THE BEST WAY I CAN FIND TO HANDLE NULL VALUES */

		if (!IS_NULL($mon_from) && !ValidTime($mon_from)) { $this->Errors("Mon From Invalid"); return False; }
		$mon_from=DBNull($mon_from);
		if (!IS_NULL($mon_to) && !ValidTime($mon_to)) { $this->Errors("Mon To Invalid"); return False; }
		$mon_to=DBNull($mon_to);

		if (!IS_NULL($tue_from) && !ValidTime($tue_from)) { $this->Errors("Tue From Invalid"); return False; }
		$tue_from=DBNull($tue_from);
		if (!IS_NULL($tue_to) && !ValidTime($tue_to)) { $this->Errors("Tue To Invalid"); return False; }
		$tue_to=DBNull($tue_to);

		if (!IS_NULL($wed_from) && !ValidTime($wed_from)) { $this->Errors("Wed From Invalid"); return False; }
		$wed_from=DBNull($wed_from);
		if (!IS_NULL($wed_to) && !ValidTime($wed_to)) { $this->Errors("Wed To Invalid"); return False; }
		$wed_to=DBNull($wed_to);

		if (!IS_NULL($thur_from) && !ValidTime($thur_from)) { $this->Errors("Thur From Invalid"); return False; }
		$thur_from=DBNull($thur_from);
		if (!IS_NULL($thur_to) && !ValidTime($thur_to)) { $this->Errors("Thur To Invalid"); return False; }
		$thur_to=DBNull($thur_to);

		if (!IS_NULL($fri_from) && !ValidTime($fri_from)) { $this->Errors("Fri From Invalid"); return False; }
		$fri_from=DBNull($fri_from);
		if (!IS_NULL($fri_to) && !ValidTime($fri_to)) { $this->Errors("Fri To Invalid"); return False; }
		$fri_to=DBNull($fri_to);

		if (!IS_NULL($sat_from) && !ValidTime($sat_from)) { $this->Errors("Sat From Invalid"); return False; }
		$sat_from=DBNull($sat_from);
		if (!IS_NULL($sat_to) && !ValidTime($sat_to)) { $this->Errors("Sat To Invalid"); return False; }
		$sat_to=DBNull($sat_to);

		if (!IS_NULL($sun_from) && !ValidTime($sun_from)) { $this->Errors("Sun From Invalid"); return False; }
		$sun_from=DBNull($sun_from);
		if (!IS_NULL($sun_to) && !ValidTime($sun_to)) { $this->Errors("Sun To Invalid"); return False; }
		$sun_to=DBNull($sun_to);


		$db=$GLOBALS['db'];
		$sql="UPDATE ".$GLOBALS['database_prefix']."facility_hours
					SET mon_from = ".$mon_from.",
					mon_to = ".$mon_to.",
					tue_from = ".$tue_from.",
					tue_to = ".$tue_to.",
					wed_from = ".$wed_from.",
					wed_to = ".$wed_to.",
					thur_from = ".$thur_from.",
					thur_to = ".$thur_to.",
					fri_from = ".$fri_from.",
					fri_to = ".$fri_to.",
					sat_from = ".$sat_from.",
					sat_to = ".$sat_to.",
					sun_from = ".$sun_from.",
					sun_to = ".$sun_to."
					WHERE facility_id = ".$facility_id."
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->Errors("Error not deleted.");
			return False;
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