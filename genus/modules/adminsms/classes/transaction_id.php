<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class TransactionID {

	function __construct() {
		$this->debug=False;
	}
	public function SetParameters($transaction_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($transaction_id)) { $this->Errors("Invalid transaction"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->transaction_id=$transaction_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info();

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."sms_space_transactions
					WHERE la.transaction_id = '".$this->transaction_id."'
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
		$this->Debug("Adding Transaction Now...");
		if (EMPTY($this->transaction_description)) { $this->Errors("Please enter a description!"); return False; }
		if (EMPTY($this->account_balance_change) || !IS_NUMERIC($this->account_balance_change)) { $this->Errors("Invalid transaction amount"); return False; }

		if (EMPTY($this->teamspace_id)) { $this->teamspace_id="NULL"; }

		$account_balance=$this->GetSpaceBalance();
		if (!$account_balance) { // do this for the first entry for all accounts
			$account_balance=$this->account_balance_change;
		}
		else {
			$account_balance+=$this->account_balance_change;
		}
		//echo "account balance: ".$account_balance."<br />";

		/* MIGHT NOT EXIST - IN THE CASE OF ADMIN */
		if (ISSET($this->sms_id)) {
			$sms_id=$this->sms_id;
		}
		else {
			$sms_id="(NULL)";
		}

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."adminsms_space_transactions
					(workspace_id,teamspace_id,transaction_description,account_balance_change,account_balance,date_transaction,user_id,sms_id)
					VALUES (
					'".$this->workspace_id."',
					".$this->teamspace_id.",
					'".$this->transaction_description."',
					'".$this->account_balance_change."',
					".$account_balance.",
					now(),
					".$_SESSION['user_id'].",
					'".$sms_id."'
					)";
		//echo $sql;
		$result=$db->query($sql);
		//$result=True; // enable for debugging
		if ($result) {
			return True;
		}
		else {
			return False;
		}
	}

	public function GetFormPostedValues() {
		$all_form_fields=array("workspace_id","teamspace_id","transaction_description","account_balance_change");

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

	public function GetSpaceBalance() {

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="SELECT account_balance
					FROM ".$GLOBALS['database_prefix']."adminsms_space_transactions ast
					WHERE workspace_id = ".$this->workspace_id."
					AND teamspace_id ".$this->TeamspaceIsNull()."
					ORDER BY transaction_id DESC
					LIMIT 1
					";
		//echo "space balance query: ".$sql."<br />";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
			return $row['account_balance'];
			}
		}
		return False;
	}

	public function SetVariable($variable,$variable_val) {
		$this->$variable=EscapeData($variable_val);
	}

	public function Debug($desc) {
		if ($this->debug==True) {
			echo $desc."<br>\n";
		}
	}

	private function TeamspaceIsNull() {

		if (EMPTY($this->teamspace_id) || $this->teamspace_id == 0) {
			return "IS NULL";
		}
		else {
			return "= ".$this->teamspace_id;
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