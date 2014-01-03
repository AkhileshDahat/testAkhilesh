<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/date_time/valid_date.php";
require_once $GLOBALS['dr']."modules/helpdesk/classes/plugins.php";
require_once $GLOBALS['dr']."modules/helpdesk/functions/misc/ticket_history.php";

class TicketID {

	function __construct() {
		//$this->ticket_id=0;
		//settype($this->ticket_id,"integer");
		$this->errors="";
	}

	public function SetParameters($ticket_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($ticket_id)) { $this->Errors("Invalid Ticket"); return False; }
		if (!RowExists("helpdesk_tickets","ticket_id",$ticket_id,"AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Invalid Ticket [2]"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->ticket_id=$ticket_id;

		/* CALL THE DOCUMENTID INFORMATION - WE NEED THIS BEFORE RUNNING THE BELOW CHECK ON SECURITY*/
		$this->Info();
		$this->LeftJoinLocation();
		$this->LeftJoinUserProblem();

		/* PRIVILEGE - NOT IMPLEMENTED */
		//if (!$this->CheckACL()) { $this->Errors("Access Denied"); return False; }

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	private function Info() {

		$db=$GLOBALS['db'];

		$sql="SELECT ht.*, hsm.*,
					um.full_name as user_id_logging_name

					FROM ".$GLOBALS['database_prefix']."helpdesk_tickets ht, ".$GLOBALS['database_prefix']."helpdesk_status_master hsm,
					".$GLOBALS['database_prefix']."helpdesk_category_master hcm, ".$GLOBALS['database_prefix']."core_user_master um
					WHERE ht.ticket_id = '".$this->ticket_id."'
					AND ht.status_id = hsm.status_id
					AND ht.category_id = hcm.category_id
					AND ht.user_id_logging = um.user_id
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				/* HERE WE CALL THE FIELDS AND SET THEM INTO DYNAMIC VARIABLES */
				$arr_cols=$db->GetColumns($result);
				for ($i=1;$i<count($arr_cols);$i++) {
					$col_name=$arr_cols[$i];
					$this->$col_name=$row[$col_name];
					// FOR PLUGINS
					$GLOBALS[$col_name] = $row[$col_name];
				}
			}
		}
	}

	private function LeftJoinLocation() {

		$db=$GLOBALS['db'];
		$this->location_name="";

		$sql="SELECT location_name
					FROM ".$GLOBALS['database_prefix']."hrms_location_master
					WHERE location_id = '".$this->location_id."'
					";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$this->location_name=$row['location_name'];
			}
		}
	}

	private function LeftJoinUserProblem() {

		$db=$GLOBALS['db'];
		$this->user_id_problem_name="";

		$sql="SELECT full_name as user_id_problem_name
					FROM ".$GLOBALS['database_prefix']."core_user_master
					WHERE user_id = '".$this->user_id_problem."'
					";
		//echo $sql;
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$this->user_id_problem_name=$row['user_id_problem_name'];
			}
		}
	}

	public function CheckACL() {
		$db=$GLOBALS['db'];

		$sql="SELECT 'x'
					FROM ".$GLOBALS['database_prefix']."Ticket_users wu,".$GLOBALS['database_prefix']."Ticket_role_master wrm
					WHERE wu.ticket_id = ".$this->ticket_id."
					AND wu.user_id = ".$_SESSION['user_id']."
					AND wu.role_id = wrm.role_id
					AND wrm.manage_Tickets = 'y'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			return True;
		}
		else {
			return False;
		}
	}

	public function Add($category_id,$priority_id,$location_id,
											$user_problem_name,$user_problem_contact_tel_no,$user_problem_contact_email,
											$title,$description,
											$date_due) {

		/* CHECKS */
		if (!RowExists("helpdesk_category_master","category_id",$category_id,"AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Invalid Category [1]"); return False; }
		if (!RowExists("helpdesk_priority_master","priority_id",$priority_id,"AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Invalid Priority [2]"); return False; }
		if (!EMPTY($location_id) && !RowExists("hrms_location_master","location_id",$location_id,"AND workspace_id=".$GLOBALS['workspace_id'])) { $this->Errors("Invalid Location [3]"); return False; }
		if (!EMPTY($user_id_problem) && !RowExists("core_user_master","user_id",$user_id_problem,"AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Invalid Location [3]"); return False; }

		if (!EMPTY($date_due) && !ValidDate($date_due)) { $this->Errors("Invalid Due Date [4]"); return False; }
		if (EMPTY($date_due)) { $date_due = "null"; } else { $date_due = "'".$date_due."'"; }
		$status_id=GetColumnValue("status_id","helpdesk_status_master","is_new_default","y","AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql']);

		if ($status_id < 1) { $this->Errors("System not setup. Status not set [6]"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		/* SOME VARIABLES WE CALCULATE */
		$submit_month=date("m");

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."helpdesk_tickets
					(workspace_id,teamspace_id,
					category_id,priority_id,location_id,status_id,
					user_id_logging,
					user_problem_name,user_problem_contact_tel_no,user_problem_contact_email,
					title,description,
					date_submit,date_due,submit_month)
					VALUES (
					".$GLOBALS['workspace_id'].",".$GLOBALS['teamspace_id'].",
					".$category_id.",".$priority_id.",'".$location_id."',".$status_id.",
					".$_SESSION['user_id'].",
					'".EscapeData($user_problem_name)."','".EscapeData($user_problem_contact_tel_no)."','".EscapeData($user_problem_contact_email)."',
					'".EscapeData($title)."','".EscapeData($description)."',
					sysdate(),".$date_due.",
					".$submit_month."
					)";
		//echo $sql."<br>";
		$result=$db->query($sql);
		if ($db->AffectedRows($result) > 0) {
			$this->ticket_id=$db->LastInsertID();
			//$this->TicketHistory("Ticket opened");
			TicketHistory($this->ticket_id,"Ticket opened");
			// CALL THE TICKET INFO SO THAT ANY PLUGINS HAVE THE INFORMATION AVAILABLE TO THEM
			$this->Info();

			//*******************************************************

			// DO THE PLUGIN BIT
			$GLOBALS['ticket_id'] = $this->ticket_id;
			$obj_plugin = new plugins;
			$arr_replies = $obj_plugin->NewTicketAction();
			foreach ($arr_replies as $reply) {
				if (strlen($reply) >0) {
					TicketHistory($this->ticket_id,"PLUGIN: ".$reply);
				}
			}
			//*******************************************************

			return True;
		}
		else {
			return False;
		}
	}

	public function Edit($category_id,$priority_id,$location_id,$status_id,$significance_id,
											$user_id_problem,
											$user_problem_name,$user_problem_contact_tel_no,$user_problem_contact_email,
											$title,$description,$technical_description,$solution,$technical_solution,
											$date_due,$date_start_work,$date_estimated_completion) {

		/* CHECK FOR THE TICKET ID AND PERMISSIONS */
		if (!$this->parameter_check) { $this->Errors("Parameter check failed"); return False; }

		/* CHECKS */
		if (!RowExists("helpdesk_category_master","category_id",$category_id,"AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Invalid Category [1]"); return False; }
		if (!RowExists("helpdesk_priority_master","priority_id",$priority_id,"AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Invalid Priority [2]"); return False; }
		if (!RowExists("helpdesk_status_master","status_id",$status_id,"AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Invalid Priority [2]"); return False; }
		if ($significance_id > 0 && !RowExists("helpdesk_significance_master","significance_id",$significance_id,"AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Invalid Significance [4]"); return False; }
		if (!EMPTY($location_id) && !RowExists("hrms_location_master","location_id",$location_id,"AND workspace_id=".$GLOBALS['workspace_id'])) { $this->Errors("Invalid Location [3]"); return False; }
		if (!EMPTY($user_id_problem) && !RowExists("core_user_master","user_id",$user_id_problem,"AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Invalid Location [3]"); return False; }

		if (!EMPTY($date_due) && $date_due != "0000-00-00 00:00:00" && !ValidDate($date_due)) { $this->Errors("Invalid Due Date [4]"); return False; }

		//$status_id=GetColumnValue("status_id","helpdesk_status_master","is_new_default","y","AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql']);

		if ($status_id < 1) { $this->Errors("System not setup. Status not set [6]"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		/* SOME VARIABLES WE CALCULATE */
		$submit_month=date("m");

		$sql="UPDATE ".$GLOBALS['database_prefix']."helpdesk_tickets
					SET	category_id = '".$category_id."',
					priority_id = '".$priority_id."',
					location_id = '".$location_id."',
					status_id = '".$status_id."',
					significance_id = '".$significance_id."',
					user_id_problem = '".$user_id_problem."',
					user_problem_name = '".$user_problem_name."',
					user_problem_contact_tel_no = '".$user_problem_contact_tel_no."',
					user_problem_contact_email = '".$user_problem_contact_email."',
					title = '".$title."',
					description = '".$description."',
					technical_description = '".$technical_description."',
					solution = '".$solution."',
					technical_solution = '".$technical_solution."',
					date_due = '".$date_due."',
					date_start_work = '".$date_start_work."',
					date_estimated_completion = '".$date_estimated_completion."'
					WHERE ticket_id = ".$this->ticket_id."
					";
		//echo $sql."<br>";
		$result=$db->query($sql);
		if ($db->AffectedRows($result) > 0) {
			//$this->TicketHistory("Ticket edited");
			TicketHistory($this->ticket_id,"Ticket edited");

			// CALL THE TICKET INFO SO THAT ANY PLUGINS HAVE THE INFORMATION AVAILABLE TO THEM
			$this->Info();

			//*******************************************************

			// DO THE PLUGIN BIT
			$GLOBALS['ticket_id'] = $this->ticket_id;
			$obj_plugin = new plugins;
			$arr_replies = $obj_plugin->EditTicketAction();
			foreach ($arr_replies as $reply) {
				if (strlen($reply) >0) {
					TicketHistory($this->ticket_id,"PLUGIN: ".$reply);
				}
			}
			//*******************************************************

			return True;
		}
		else {
			return False;
		}
	}



	public function LockTicket() {
		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		if (!$this->parameter_check) { $this->errors("Failed to lock ticket"); return False;}

		/* CHECK FOR A LOCK FIRST */
		$sql="SELECT 'x'
					FROM ".$GLOBALS['database_prefix']."helpdesk_tickets_locked
					WHERE ticket_id = '".$this->ticket_id."'
					";
		$result = $db->Query($sql);
		if ($db->NumRows($result) == 0) {

			$sql="INSERT INTO ".$GLOBALS['database_prefix']."helpdesk_tickets_locked
						(ticket_id,user_id,date_locked)
						VALUES (
						".$this->ticket_id.",
						".$_SESSION['user_id'].",
						sysdate()
						)";
			//echo $sql."<br>";
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				return True;
			}
			else {
				return False;
			}
		}
	}

	public function TicketLockedForCurrentUser() {
		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		if (!$this->parameter_check) { $this->errors("Failed to lock ticket"); return False;}

		/* CHECK FOR A LOCK FIRST */
		$sql="SELECT 'x'
					FROM ".$GLOBALS['database_prefix']."helpdesk_tickets_locked
					WHERE ticket_id = '".$this->ticket_id."'
					AND user_id != ".$_SESSION['user_id']."
					";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			return True;
		}
		else {
			return False;
		}
	}

	public function AddTicketDelegation($user_id) {

		/* CHECKS */
		if (!IS_NUMERIC($user_id)) { $this->Errors("Invalid User!"); return False; }
		if (!$this->parameter_check) { $this->Errors("Invalid Parameter Check."); return False; }
		if (RowExists("helpdesk_ticket_delegation","ticket_id",$this->ticket_id,"AND user_id =".$user_id)) { $this->Errors("User Exists"); return False; }
		if (!RowExists("core_space_users","user_id",$user_id,"AND workspace_id=".$GLOBALS['workspace_id'])) { $this->Errors("Invalid user on workspace"); return False; }

		/* GET THE USER'S FULL NAME */
		$del_user_full_name=GetColumnValue("full_name","core_user_master","user_id",$user_id,"");

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."helpdesk_ticket_delegation
					(ticket_id,user_id,date_delegated)
					VALUES (
					".$this->ticket_id.",
					".$user_id.",
					sysdate()
					)";
		//echo $sql."<br>";
		$result=$db->query($sql);
		if ($db->AffectedRows($result) > 0) {
			//$this->TicketHistory("Ticket delegated to ".$del_user_full_name);
			TicketHistory($this->ticket_id,"Ticket delegated to ".$del_user_full_name);
			return True;
		}
		else {
			return False;
		}
	}

	public function DeleteUserDelegation($user_id) {

		/* CHECKS */
		if (!IS_NUMERIC($user_id)) { $this->Errors("Invalid User!"); return False; }

		/* GET THE USER'S FULL NAME */
		$del_user_full_name=GetColumnValue("full_name","core_user_master","user_id",$user_id,"");

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			$sql="DELETE
						FROM ".$GLOBALS['database_prefix']."helpdesk_ticket_delegation
						WHERE ticket_id = ".$this->ticket_id."
						AND user_id = ".$user_id."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				//$this->TicketHistory("Ticket delegation removed from ".$del_user_full_name);
				TicketHistory($this->ticket_id,"Ticket delegation removed from ".$del_user_full_name);
				return True;
			}
			else {
				$this->Errors("Failed to delete");
				return False;
			}
		}
		else {
			$this->Errors("Sorry, parameter check failed<br>");
			return False;
		}
	}

	public function AddTicketTag($tag_id) {

		/* CHECKS */
		if (!IS_NUMERIC($tag_id)) { $this->Errors("Invalid User!"); return False; }
		if (!$this->parameter_check) { $this->Errors("Invalid Parameter Check."); return False; }
		if (RowExists("helpdesk_ticket_tags","ticket_id",$this->ticket_id,"AND tag_id =".$tag_id)) { $this->Errors("Tag Exists"); return False; }
		if (!RowExists("helpdesk_tag_master","tag_id",$tag_id,"AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Invalid tag on workspace"); return False; }

		/* GET THE TAG NAME */
		$del_user_full_name=GetColumnValue("tag_name","helpdesk_tag_master","tag_id",$tag_id,"");

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."helpdesk_ticket_tags
					(ticket_id,tag_id)
					VALUES (
					".$this->ticket_id.",
					".$tag_id."
					)";
		//echo $sql."<br>";
		$result=$db->query($sql);
		if ($db->AffectedRows($result) > 0) {
			//$this->TicketHistory("Ticket tag added: ".$del_user_full_name);
			TicketHistory($this->ticket_id,"Ticket tag added: ".$del_user_full_name);
			return True;
		}
		else {
			return False;
		}
	}

	public function DeleteTicketTag($tag_id) {

		/* CHECKS */
		if (!IS_NUMERIC($tag_id)) { $this->Errors("Invalid User!"); return False; }

		/* GET THE USER'S FULL NAME */
		$del_tag_name=GetColumnValue("tag_name","helpdesk_tag_master","tag_id",$tag_id,"");

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			$sql="DELETE
						FROM ".$GLOBALS['database_prefix']."helpdesk_ticket_tags
						WHERE ticket_id = ".$this->ticket_id."
						AND tag_id = ".$tag_id."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				//$this->TicketHistory("Ticket tag removed: ".$del_tag_name);
				TicketHistory($this->ticket_id,"Ticket tag removed: ".$del_tag_name);
				return True;
			}
			else {
				$this->Errors("Failed to delete");
				return False;
			}
		}
		else {
			$this->Errors("Sorry, parameter check failed<br>");
			return False;
		}
	}

	public function Delete() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			$sql="DELETE
						FROM ".$GLOBALS['database_prefix']."document_categories
						WHERE ticket_id = ".$this->ticket_id."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				return True;
			}
			else {
				$this->Errors("Failed to delete");
				return False;
			}
		}
		else {
			$this->Errors("Sorry, document category role parameter check failed<br>");
			return False;
		}
	}

	/* GET A COLUMN NAME FROM THE ARRAY */
	public function GetColVal($col_name) {
		if (ISSET($this->$col_name)) {
			return $this->$col_name;
		}
		else {
			return "";
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