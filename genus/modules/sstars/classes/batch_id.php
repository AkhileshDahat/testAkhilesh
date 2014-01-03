<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/date_time/valid_date.php";
require_once $GLOBALS['dr']."include/functions/date_time/mysql_to_seconds.php";
require_once $GLOBALS['dr']."include/functions/date_time/timestamp_to_mysql.php";

class BatchID {

	function __construct() {
		$this->debug=False;
	}

	public function SetParameters($batch_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($batch_id)) { $this->Errors("Invalid batch"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->batch_id=$batch_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info();

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."sstars_batch sb
					WHERE sb.batch_id = '".$this->batch_id."'
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

	public function Add() {

		/* CHECKS */
		$this->Debug("Starting Checks for empty fields");

		if(EMPTY($this->invoice_number)) { $this->Errors("Invalid invoice number!"); return False; }
		if (RowExists("sstars_batch","invoice_number","'".$this->invoice_number."'","AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Invoice number has been used"); return False; }
		if (EMPTY($this->cost_price)) { $this->Errors("Invalid cost price!"); return False; }
		if (EMPTY($this->date_purchase) || !ValidDate($this->date_purchase)) { $this->Errors("Invalid or empty date of purchase!"); return False; }
		if (EMPTY($this->date_delivery) || !ValidDate($this->date_delivery)) { $this->Errors("Invalid or empty date of delivery!"); return False; }

		$this->Debug("Ended Checks for empty fields");

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."sstars_batch
					(added_user_id,invoice_number,description,cost_price,date_purchase,date_delivery,vendor_id,workspace_id,teamspace_id)
					VALUES (
					".$_SESSION['user_id'].",
					'".$this->invoice_number."',
					'".$this->description."',
					'".$this->cost_price."',
					'".$this->date_purchase."',
					'".$this->date_delivery."',
					'".$this->vendor."',
					".$GLOBALS['workspace_id'].",
					".$GLOBALS['teamspace_id']."
					)";
		//echo $sql;
		$result=$db->query($sql);
		//$result=True;
		if ($result) {
			$this->SetParameters($db->LastInsertID());
			//$this->DoWorkflow(); // not implemented
			return True;
		}
		else {
			return False;
		}
	}

	public function GetFormPostedValues() {
		$all_form_fields=array("invoice_number","description","cost_price","date_purchase","date_delivery",
													"vendor");

		for ($i=0;$i<count($all_form_fields);$i++) {

			//echo $_POST['batch_id'];
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
	/* NOT IMPLEMENTED YET */
	public function DoWorkflow() {
		$db=$GLOBALS['db'];
		//echo "Doing workflow<br>";
		/* WORKFLOW WORKER CLASS */
		$obj_app_wf=new ApplicationWorkflow;
		$obj_app_wf->SetParameters($this->batch_id);

		$sql="SELECT lw.perform_action, lw.workflow_order, lwd.do_next_step, lwd.is_final_step
					FROM ".$GLOBALS['database_prefix']."leave_applications la,".$GLOBALS['database_prefix']."leave_workflow lw,
					".$GLOBALS['database_prefix']."leave_workflow_detail lwd
					WHERE la.batch_id = '".$this->batch_id."'
					AND la.workflow_order = lw.workflow_order
					AND lw.workspace_id = ".$GLOBALS['workspace_id']."
					AND lw.perform_action = lwd.perform_action
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				/* FOR EACH APPLICATION LETS SEE WHAT WE HAVE TO DO IN THE WORKER CLASS */
				if ($row['perform_action']=="HOD Approval") {
					echo "Inserting HOD Approval<br>";
					$obj_app_wf->HodApproval();
				}
				elseif ($row['perform_action']=="Email HOD") {
					echo "Inserting HOD Email<br>";
					$obj_app_wf->EmailHod();
				}
				elseif ($row['perform_action']=="Email Global Approver") {
					echo "Inserting Global Email<br>";
					$obj_app_wf->EmailGlobal();
				}
				elseif ($row['perform_action']=="Global Approval") {
					echo "Inserting Global Approval<br>";
					$obj_app_wf->GlobalApproval();
				}
				else {
					//echo "No workflow detected";
				}

				/* UPDATE THE APPLICATION TO THE STAGE OF THE WORKFLOW */
				$sql1="UPDATE ".$GLOBALS['database_prefix']."leave_applications la
							SET workflow_order = workflow_order +1
							WHERE batch_id = '".$this->batch_id."'
							";
				$result_update_count=$db->Query($sql1);

				/* CHECK IF WE NEED TO DO THIS AGAIN */
				if ($row['do_next_step']=="y") {
					$this->DoWorkFlow();
				}
			}
		}
		else {
			echo "no workflow detected [2]";
		}
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