<?php
require_once $GLOBALS['dr']."modules/scheduling/classes/scheduling_lecturer_reserved_slots.php";

require_once $GLOBALS['dr']."modules/scheduling/classes/resource_id.php";
require_once $GLOBALS['dr']."modules/scheduling/classes/subject_detail_id.php";

class Scheduling {

	public function __construct() {
		/* DEBUGGING */
		$this->show_debug=True;
		$this->errors="";

		/* IMPORT GLOBAL SETTINGS */
		global $config_working_week_days;
		$this->config_working_week_days = $config_working_week_days;
		global $config_start_time;
		$this->config_start_time = $config_start_time;
		global $config_end_time;
		$this->config_end_time = $config_end_time;
		global $config_interval;
		$this->config_interval = $config_interval;

		/* THIS STORES THE ARRAY OF SUBJECTS WE NEED TO PLACE */
		$this->arr_subject_details="";

		/* AN ARRAY OF CAPACITY VENUES */
		$this->arr_venue_capacity=array();

		/* SOME OTHER OBJECTS TO BE USED */
		$this->obj_slrs=new SchedulingLecturerReservedSlots;
	}

	public function RunSchedule() {

		$this->debug("Starting GetSubjectDetail method","","START");
		$v=$this->GetSubjectDetail();
		$this->debug("Starting GetSubjectDetail method","","END");
		return $v;

	}
	/*
	+++ STEP 1 +++
	HERE WE SELECT ALL THE SUBJECTS THAT HAVE NOT BEEN ALLOCATED
	*/
	private function GetSubjectDetail() {
		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$var_return_code=True;

		$sql="SELECT subject_detail_id,description,user_id,date_start,date_end,duration_hours,capacity
					FROM ".$GLOBALS['database_prefix']."scheduling_subject_detail
					WHERE resource_booking_id IS NULL
					ORDER BY user_id
					";
		$this->debug($sql,"","SQL");
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {

				$this->arr_subject_details["subject_detail_id"]=$row['subject_detail_id'];
				$this->arr_subject_details["description"]=$row['description'];
				$this->arr_subject_details["user_id"]=$row['user_id'];
				$this->arr_subject_details["date_start"]=$row['date_start'];
				$this->arr_subject_details["date_end"]=$row['date_end'];
				$this->arr_subject_details["duration_hours"]=$row['duration_hours'];
				$this->arr_subject_details["capacity"]=$row['capacity'];


				//$this->debug("Starting MatchSubjectLecturers method",$row['user_id'],"LECTSTART");
				//$this->arr_subject_details=$db->FetchArray($result);
				//print_r($this->arr_subject_details);
				$v=$this->MatchSubjectLecturers();
				if (!$v) {
					$var_return_code=False;
				}
			}
		}
		else {
			$this->Errors("No subjects to run schedule for");
			$var_return_code=False;
		}

		return $var_return_code;
	}
	/*
	+++ STEP 2 +++
	THIS FUNCTION GETS THE TIMETABLE FOR EACH SUBJECT FOR THE LECTURER INVOLVED
	*/
	private function MatchSubjectLecturers() {

		//$row=$this->arr_subject_details;
		/* WE ARE LOOPING THE LIST OF SUBJECTS WITH THEIR CORRESPONDING DATA */
		//foreach ($row as $key => $val) {

			$this->debug("<hr>","","HTML");

			/* INITIAL DEBUG TO DESCRIBE THE SUBJECT WE'RE DEALING WITH */
			$this->debug("Starting check for subject ID ".$this->arr_subject_details['subject_detail_id']." desc: ".$this->arr_subject_details['description']." for ".$this->arr_subject_details['duration_hours']." hours",$this->arr_subject_details["user_id"],"SUBJECT");

			/* THIS MUST COME BEFORE THE GetLecturerFreeSlots METHOD */
			$this->debug("Initialising all resources for at least ".$this->arr_subject_details['capacity']." people",$this->arr_subject_details["user_id"],"RESOURCES");
			$result=$this->SetResourcesAvailable($this->arr_subject_details['capacity']);
			if (!$result) { $this->Errors("No available resources"); return false; }

			/* GET ALL THE FREE SLOTS FOR THE LECTURER TEACHING THE SUBJECT */
			$this->debug("Initialising lecturer free slots",$this->arr_subject_details["user_id"],"SLOTS");
			$result=$this->InitLecturerFreeSlots($this->arr_subject_details['user_id'],$this->arr_subject_details['duration_hours']);
			if (!$result) { $this->Errors("The lecturer [ID:".$this->arr_subject_details['user_id']."] has no free slots"); return false; }

			/* SEARCH FOR AN AVAILABLE RESOURCE */
			$this->debug("Now searching available resources",$this->arr_subject_details["user_id"],"RESOURCES");
			$v_resource_id_found=$this->MatchResourceAvailability($this->found_lect_available["dow"],$this->found_lect_available["start_time"],$this->found_lect_available["end_time"]);
			if (!$v_resource_id_found) { $this->Error("There are no available venues"); return false; }
			else {
				$this->debug("Found an available venue: ".$v_resource_id_found." on ".$this->found_lect_available["dow"]." at ".$this->found_lect_available["start_time"]." to ".$this->found_lect_available["end_time"]." lets start saving the slot",$this->arr_subject_details["user_id"],"RESOURCES");
			}

			/* LETS RESERVE THE SLOT IN THE RESOURCES BOOKING */
			$obj_ri=new ResourceID;
			$result=$obj_ri->ResourceBookingIDAdd($v_resource_id_found,$this->arr_subject_details["date_start"],$this->arr_subject_details["date_end"],$this->found_lect_available["dow"],$this->found_lect_available["start_time"],$this->found_lect_available["end_time"],$this->arr_subject_details["description"],$this->arr_subject_details["subject_detail_id"]);
			if (!$result) { $this->Errors($obj_ri->ShowErrors()); return false; }
			else {

				$this->debug("Found venue and booked: ".$v_resource_id_found." with booking id: ".$obj_ri->GetInfo("last_insert_resource_booking_id"),$this->arr_subject_details["user_id"],"RESOURCES");
			}

			/* UPDATE THE BOOKING ID BACK TO THE SCHEDULING TABLE */
			$result=$this->UpdateSchedulingSubjectDetail($obj_ri->GetInfo("last_insert_resource_booking_id"),$this->arr_subject_details["subject_detail_id"]);
			if (!$result) { $this->Errors("Unable to save the stored venue into the scheduling table"); return false; }
			else {
				$this->debug("Updated the booking ID into the scheduling table",$this->arr_subject_details["user_id"],"RESOURCES");
			}


			$this->debug("<hr>","","HTML");
		//}
	}
	/*
	THIS FUNCTION CREATES A 3D ARRAY CONSISTING OF THE LECTURER+DAYOFWEEK+TIMESLOT INTERVAL
	*/
	private function InitLecturerFreeSlots($user_id,$duration_hours) {

		$this->arr_lecturer_free_slots=array();

		foreach ($this->config_working_week_days as $dow) {
			//echo $dow."<br>";
			//echo $this->config_start_time."<br>";
			//echo $this->config_end_time."<br>";
			//echo $this->config_interval."<br>";
			//echo $dow."<br>";
			for ($i=$this->config_start_time;$i<$this->config_end_time;$i+=$this->config_interval) {
				$this->arr_lecturer_free_slots[$user_id][$dow][$i]="A";
				//$this->debug("**INIT** Reserving slot for user: *$user_id* on a *$dow* at *$i*",$this->arr_subject_details["user_id"],"SLOTS");
				/*
				$this->debug($user_id);
				$this->debug($dow);
				$this->debug($i);
				*/
			}
		}

		/* CALL THE METHOD BELOW TO UPDATE THE ARRAY SLOTS TO "R" */
		$this->debug("Reserving lecturer slots",$this->arr_subject_details["user_id"],"SLOTS");
		$this->SetLecturerFreeSlots($user_id);

		/* UPDATE THE ARRAY WITH THE BOOKED SLOTS */
		$this->SetLecturerBookedSlots($user_id);

		/* CALL THE WORKER CLASS TO RETURN US A FREE SLOT FROM THE ARRAY */
		$this->debug("Getting lecturer free slots for: ".$duration_hours." hours",$this->arr_subject_details["user_id"],"SLOTS");
		$result=$this->GetLecturerFreeSlots($user_id,$duration_hours);

		return $result;

	}
	/*	THIS FUNCTION GETS ALL THE LECTURER RESERVED SLOTS FROM THE DATABASE AND UPDATES THE INIT ARRAY FOR THE LECTURER SLOTS	*/
	private function SetLecturerFreeSlots($user_id) {

		$this->debug("Method for reserving slots about to start...",$this->arr_subject_details["user_id"],"SLOTS");

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="SELECT time_start,day_of_week
					FROM ".$GLOBALS['database_prefix']."scheduling_lecturer_reserved_slots
					WHERE user_id = ".$user_id."
					ORDER BY day_of_week,time_start
					";
		$this->debug($sql,"","SQL");
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				//echo "a<br>";
				$dow=$row['day_of_week'];
				$time_start=$this->GetMySQLTimeToSeconds($row['time_start']);
				//echo $row['day_of_week']."<br />";
				//echo $time_start."<br />";
				/*
				if (ISSET($this->arr_lecturer_free_slots[$user_id][$dow][$time_start])) {
					$this->debug("Reserving slot for user: *$user_id* on a *$dow* at *$time_start*",$this->arr_subject_details["user_id"],"SLOT");
					$this->debug("Slot exists, about to reserve it");
				}
				else {
					$this->debug("Check ** Reserving slot for user: *$user_id* on a *$dow* at *$time_start*");
					$this->debug("Slot array does not exist");
				}
				*/

				$this->arr_lecturer_free_slots[$user_id][$dow][$time_start]="R";
				//
				/*
				$this->debug($user_id);
				$this->debug($dow);
				$this->debug($time_start);
				*/
			}
		}
		return True;
	}
	/*	THIS FUNCTION GETS ALL THE LECTURER'S SUBJECTS THAT HAVE BEEN BOOKED */
	private function SetLecturerBookedSlots($user_id) {

		$this->debug("Method for checking lecturer's reserved slots about to start...",$this->arr_subject_details["user_id"],"SLOTS");

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="SELECT rbm.time_start, rbm.dow as day_of_week
					FROM ".$GLOBALS['database_prefix']."scheduling_subject_detail ssd, ".$GLOBALS['database_prefix']."resource_booking_master rbm
					WHERE ssd.user_id = ".$user_id."
					AND ssd.resource_booking_id = rbm.resource_booking_id
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$dow=$row['day_of_week'];
				$time_start=$this->GetMySQLTimeToSeconds($row['time_start']);
				$this->arr_lecturer_free_slots[$user_id][$dow][$time_start]="R";
			}
		}
		return True;
	}
	/* THIS FUNCTION LOOPS THROUGH ALL THE AVAILABLE SLOTS LOOKING FOR THE DURATION THAT IS AVAILABLE */
	private function GetLecturerFreeSlots($user_id,$duration) {

		$this->found_lect_available=array();

		/* LOOP THROUGH ALL DAYS IN THE WEEK */
		foreach ($this->config_working_week_days as $dow) {
			/* LOOP THROUGH EACH DAY'S START TIME UNTIL THE END TIME (8:30-5:30) */
			for ($i=$this->config_start_time;$i<$this->config_end_time;$i+=$this->config_interval) {
				/*
				$this->debug($user_id);
				$this->debug($dow);
				$this->debug($i);
				*/
				//$this->debug($this->arr_lecturer_free_slots[$user_id][$dow][$i]);
				if ($this->arr_lecturer_free_slots[$user_id][$dow][$i]=="A") {
					/* WE HAVE FOUND A TIME THAT IS AVAILABLE NOW LETS CHECK THE DURATION AND THEREFORE THE NEXT SLOT MUST BE AVAILABLE TOO */
					$end_slot_time_start=$i+($duration*$this->config_interval);
					//if (ISSET($this->arr_lecturer_free_slots[$user_id][$dow][$end_slot_time_start]) && $this->arr_lecturer_free_slots[$user_id][$dow][$end_slot_time_start]=="A") {
					$this->debug("Found an available slot on $dow at ".$this->GetSecondsFromMySQL($i),$this->arr_subject_details["user_id"],"SLOTS");
					//$this->debug("Checking end slot: ".$this->GetSecondsFromMySQL($end_slot_time_start));


					$var_all_slots_available=True;

					if ($duration>1) {
						$var_start_from_second_slot=($i+$this->config_interval);
						for ($j=$var_start_from_second_slot;$j<$end_slot_time_start;$j+=$this->config_interval) {
							$this->debug("The first slot is available, lets check if the next is, depending on whether this subject has more than 1 slot",$this->arr_subject_details["user_id"],"SLOTS");
							$this->debug("Next slot is: ".$this->GetSecondsFromMySQL($j),$this->arr_subject_details["user_id"],"SLOTS");
							if (!ISSET($this->arr_lecturer_free_slots[$user_id][$dow][$j]) || $this->arr_lecturer_free_slots[$user_id][$dow][$j]!="A") {
								$this->debug("That slot is not available in the lecturer's timetable",$this->arr_subject_details["user_id"],"SLOT");
								$var_all_slots_available=False;
							}
						}
					}

					if ($var_all_slots_available) {
						// ALL SLOTS ARE AVAILABLE - e.g. IF 3 HOUR DURATION - 10:30, 11:30, 12:30
						// SAVE THE VARIABLES IN MYSQL FORMAT e.g 09:40
						$this->debug("All slots are available. Proceed to next step",$this->arr_subject_details["user_id"],"SLOTS");
						$v_start_time_my=$this->GetSecondsFromMySQL($i);
						$v_end_time_my=$this->GetSecondsFromMySQL($end_slot_time_start);

						/* STORE AVAILABLE SLOT IN ARRAY */
						$this->found_lect_available["dow"]=$dow;
						$this->found_lect_available["start_time"]=$v_start_time_my;
						$this->found_lect_available["end_time"]=$v_end_time_my;
						/*
						$this->debug($dow);
						$this->debug($v_start_time_my);
						$this->debug($v_end_time_my);
						*/
						return True;
					}
					else {
						$this->debug("No slots available for that combination, trying next day.",$this->arr_subject_details["user_id"],"SLOTS");
						// AT LEAST ONE SLOT IS NOT AVAILABLE SO WE NEED TO TRY ANOTHER SEQUENCE
					}

					//}
				}
				else {
					$this->debug("Lecturer: ".$user_id." is not available on a $dow at $i",$this->arr_subject_details["user_id"],"SLOTS");
				}
			}
		}
		return false;
	}
	/* THIS FUNCTION IS CALLED WHEN A USER SLOT IS FOUND */
	private function ResourceMatch($dow,$time_start,$time_end) {
		// THIS IS WHERE I AM - THURS
		return $this->MatchResourceAvailability($dow,$time_start,$time_end);
	}
	/* THIS FUNCTION GETS ALL THE RESOURCES IN CAPACITY ORDER SO THAT THE CLOSEST MATCH WILL FIT */
	private function SetResourcesAvailable($capacity) {

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."resource_master rm
					WHERE rm.capacity >= ".$capacity."
					ORDER BY capacity
					";
		echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			$this->debug("More than 1 possible venue based on size is available",$this->arr_subject_details["user_id"],"RESOURCES");
			$this->arr_venue_capacity=$db->FetchArray($result);
			echo "<br />Show Venues:<br />";
			print_r($this->arr_venue_capacity);
			//echo "<br />";echo "<br />";echo "<br />";
			/* ---------------------TO DO - INCORPORATE THE AVAILABLE VENUES ---------------------------*/
			/* CHECK WHAT THE CURRENT SUBJECT NEEDS TO UPDATE ACCORDINGLY */
			$arr_matching_resources=$this->CheckSubjectItemReqs();
			echo "Displaying matching resources<br />";
			print_r($arr_matching_resources);
			return True;
		}
		else {
			$this->debug("No venue is available for $capacity people",$this->arr_subject_details["user_id"],"ERROR");
			return false;
		}
	}
	/* THIS METHOD CHECKS WHAT IS NEEDED IN EACH VENUE, E.G LCD PC ETC */
	private function CheckSubjectItemReqs() {

		/* SET UP AN OBJECT TO THE SUBJECT_DETAIL_ID CLASS */
		$obj_sdi=new SubjectDetailID;
		$obj_sdi->SetParameters($this->arr_subject_details["subject_detail_id"]);

		$arr_items=$obj_sdi->GetItemReqsArr();
		print_r($arr_items);

		$arr_matching_resources=array();
		$v_arr_items=array();

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="SELECT DISTINCT(resource_id) as resource_id
					FROM ".$GLOBALS['database_prefix']."resource_items ri
					ORDER BY resource_id
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {


				$v_arr_items="";
				$sql1="SELECT ri.item_id, rim.item_name
							FROM ".$GLOBALS['database_prefix']."resource_items ri, ".$GLOBALS['database_prefix']."resource_item_master rim
							WHERE ri.resource_id = '".$row['resource_id']."'
							AND ri.item_id = rim.item_id
							";
				//echo $sql1."<br>";
				$result1 = $db->Query($sql1);
				if ($db->NumRows($result1) > 0) {
					while($row1 = $db->FetchArray($result1)) {
						$item_id=$row1['item_id'];
						$v_arr_items[$item_id]=$row1['item_name'];
						/*
						if (!array_key_exists($row1['item_id'],$arr_items)) {
							echo "I did not find item reqd: ".$row1['item_id']." in the list of requirements for resource id: ".$row['resource_id'];
							$found=False;
						}
						*/
					}
				}
				/*
				echo "Items that this subject has:";
				print_r($arr_items);
				echo "Items that the venue has:";
				print_r($v_arr_items);
				*/

				$found=True;
				foreach ($arr_items as $key => $val) {
					$this->debug("Searching for item: $val [$key] in the array of requirements for facilities");
					if (!array_key_exists($key,$v_arr_items)) {
						$this->debug("Resource id: ".$row['resource_id']." is NOT equipped with a $val [$key]");
						$found=False;
					}
					else {
						$this->debug("Resource id: ".$row['resource_id']." is equipped with a $val [$key]");
					}
				}
				if ($found) {
					$this->debug("Found a match in resource id: ".$row['resource_id']);
					//return $row['resource_id'];
					$arr_matching_resources=$row['resource_id'];
				}
			}
			return $arr_matching_resources;
		}
		else {
			$this->debug("No venues available to check for resources",$this->arr_subject_details["user_id"],"ERROR");
			return false;
		}
	}
	/* THIS METHOD TAKES IN THE ARRAY OF VENUES WITH A MATCHING CAPACITY AND CHECKS EACH ONE FOR AVAILABILITY	*/
	private function MatchResourceAvailability($dow,$time_start,$time_end) {
		$this->debug("Entering MatchResourceAvailability method",$this->arr_subject_details["user_id"],"RESOURCES");

		$row=$this->arr_venue_capacity;

		foreach ($row as $key => $val) {

			if ($this->ResourceAvailable($row['resource_id'],$dow,$time_start,$time_end)) {
				$this->debug("Resource ID: ".$row['resource_id']." is available on a $dow at $time_start to $time_end",$this->arr_subject_details["user_id"],"RESOURCES");
				return $row['resource_id'];
				//return True;
			}
			else {
				$this->debug("Resource ID: ".$row['resource_id']." is NOT available on a $dow at $time_start to $time_end",$this->arr_subject_details["user_id"],"RESOURCES");
				return False;
			}
		}
	}
	/* CHECK IF THE RESOURCE IS AVAILABLE AT THE TIME REQUIRED FOR THE DURATION REQUIRED */
	private function ResourceAvailable($resource_id,$dow,$time_start,$time_end) {
		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."resource_booking_master rbm
					WHERE rbm.resource_id = $resource_id
					AND rbm.repeat_day_of_week = '".$dow."'
					AND rbm.time_start = '".$time_start."'
					AND rbm.time_end = '".$time_end."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			return False;
		}
		else {
			return True;
		}
	}
	/* WE HAVE FOUND AN AVAILABLE ROOM, UPDATE THE SCHEDULING SOFTWARE WITH THE BOOKING ID */
	private function UpdateSchedulingSubjectDetail($resource_booking_id,$subject_detail_id) {
		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="UPDATE ".$GLOBALS['database_prefix']."scheduling_subject_detail
					SET resource_booking_id='".$resource_booking_id."'
					WHERE subject_detail_id='".$subject_detail_id."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) == 0) {
			return False;
		}
		else {
			return True;
		}
	}
	/* GET SECONDS FROM A MYSQL TIME STAMP SUCH AS 14:30 */
	private function GetSecondsFromMySQL($p_mysql_time) {
		$date_seconds=mktime(0, $p_mysql_time, 0, date("m")  , date("d"), date("Y"));
		return date("G:i",$date_seconds);
	}
	/* MYSQL TIME TO SECONDS */
	private function GetMySQLTimeToSeconds($time) {
		list($hr, $mn, $se) = split(':', $time);

		$total=($hr*60)+$mn;
		return $total;
	}
	/* DEBUGGING OF THE TIMETABLE */
	private function DebugDrawTT($user_id) {
		$c="<table border=1 cellpadding=5>\n";
		foreach ($this->config_working_week_days as $dow) {
			$c.="<tr>\n";
			//echo $dow."<br>";
			//echo $this->config_start_time."<br>";
			//echo $this->config_end_time."<br>";
			//echo $this->config_interval."<br>";
			for ($i=$this->config_start_time;$i<$this->config_end_time;$i+=$this->config_interval) {
				//echo $i."<br />";
				$c.="<td>".$this->arr_lecturer_free_slots[$user_id][$dow][$i]."</td>\n";
			}
			$c.="</tr>\n";
		}
		$c.="</table>\n";
		return $c;
	}
	/* DEBUG */
	private function debug($message,$user_id="",$category="") {
		if ($this->show_debug) {
			echo $message."<br />";
		}
		$sql="INSERT INTO ".$GLOBALS['database_prefix']."scheduling_log
					(logged_by_user_id,affected_user_id,message,category)
					VALUES (
					'".$_SESSION['user_id']."',
					'".$user_id."',
					'".EscapeData($message)."',
					'".$category."'
					)";
		//echo $sql;
		$GLOBALS['db']->query($sql);
	}
	private function Errors($err) {
		$this->errors.=$err."<br>";
	}
	public function ShowErrors() {
		return $this->errors;
	}
}
?>