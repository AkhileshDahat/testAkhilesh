<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/adminsms/classes/transaction_id.php";



class SMSID {

	function __construct() {
		$this->debug=False;
		global $leave_total_sundays;
		global $leave_total_saturdays;
	}
	public function SetParameters($application_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($application_id)) { $this->Errors("Invalid application"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->application_id=$application_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info();

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT la.*, lcm.*, um.full_name, lsm.*
					FROM ".$GLOBALS['database_prefix']."leave_applications la, ".$GLOBALS['database_prefix']."leave_category_master lcm,
					".$GLOBALS['database_prefix']."core_user_master um, ".$GLOBALS['database_prefix']."leave_status_master lsm
					WHERE la.application_id = '".$this->application_id."'
					AND la.category_id = lcm.category_id
					AND la.user_id = um.user_id
					";
		//echo $sql."<br>";
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

	public function GetInfo($v) {
		if (ISSET($this->$v)) {
			return $this->$v;
		}
		else {
			return "";
		}
	}

	public function Send() {

		/* CHECKS */
		$this->Debug("Sending SMS now");
		if(EMPTY($this->message)) { $this->Errors("Please enter a date!"); return False; }
		
		/* LETS COUNT BEFORE WE DO ANYTHING */
		$send_count_total=0;
		if ($this->send_all_users=="y") {
			$send_count_total+=$this->SendAllUsers(True);
		}
		$obj_ti=new TransactionID;
		$obj_ti->SetVariable("workspace_id",$GLOBALS['workspace_id']);
		$obj_ti->SetVariable("teamspace_id",$GLOBALS['teamspace_id']);
		$account_sms_balance=$obj_ti->GetSpaceBalance();
		
		if ($send_count_total > $account_sms_balance) {
			$this->Errors("Sorry, you do not have sufficient credit to send this sms!"); return False;
		}	
		
		
		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."sms_message_master
					(workspace_id,teamspace_id,message,user_id,date_sent,sent_all_users_workspace)
					VALUES (
					".$GLOBALS['workspace_id'].",
					".$GLOBALS['teamspace_id'].",					
					'".$this->message."',
					".$_SESSION['user_id'].",					
					now(),					
					'".$this->send_all_users."'
					)";
		//echo $sql;
		$result=$db->query($sql);
		//$result=True; // enable for debugging
		if ($result) {
			$this->sms_id=$db->LastInsertID();
			
			/* SEND TO ALL USERS IF REQUIRED */
			if ($this->send_all_users=="y") {
				$this->SendAllUsers();
			}
			
			/* DEDUCT FROM THEIR BALANCE */
			$obj_ti->SetVariable("transaction_description","Sent SMS: ".$this->message);
			$obj_ti->SetVariable("account_balance_change",-$send_count_total);			
			$obj_ti->SetVariable("sms_id",$this->sms_id);
			$obj_ti->Add();
			
			return True;
		}
		else {
			return False;
		}
	}
	
	private function SendAllUsers($count_only=False) {
		/* COUNT */
		$this->count=0;
		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];
		/* GET ALL USERS */
		$sql="SELECT user_id
					FROM ".$GLOBALS['database_prefix']."core_space_users csu
					WHERE workspace_id = ".$GLOBALS['workspace_id']."
					AND teamspace_id ".$GLOBALS['teamspace_sql']."
					";
		//echo $sql."<br />";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			/* DETERMINE IF WE ONLY NEED TO COUND THE NUMBER OF ROWS */
			if ($count_only) { return $db->NumRows($result); }
			while($row = $db->FetchArray($result)) {
				$this->SendSMSToUser($row['user_id']);
				$this->count++;
			}
		}
	}
	
	private function SendSMSToUser($user_id) {
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."sms_message_detail
					(sms_id,user_id,date_sent)
					VALUES (
					".$this->sms_id.",
					$user_id,					
					now()					
					)";
		//echo $sql."<br />";
		$result=$db->query($sql);		
		if ($result) {			
			return True;
		}
		else {
			return False;
		}
	}
	
	public function GetFormPostedValues() {
		$all_form_fields=array("message","send_all_users");

		for ($i=0;$i<count($all_form_fields);$i++) {

			//echo $_POST['application_id'];
			if (ISSET($_POST[$all_form_fields[$i]]) && !EMPTY($_POST[$all_form_fields[$i]])) {
				$this->SetVariable($all_form_fields[$i],$_POST[$all_form_fields[$i]]);
			}
			else {
				//echo "<br>".$all_form_fields[$i]."<br>";
				$this->$all_form_fields[$i]="";
			}
		}
	}

	public function SetVariable($variable,$variable_val) {
		$this->$variable=EscapeData($variable_val);
	}

	public function Debug($desc) {
		if ($this->debug==True) {
			echo $desc."<br>\n";
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