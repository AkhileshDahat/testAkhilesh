<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/db/get_sequence_currval.php";

class VisitorID {

	function __construct() {

	}

	public function SetParameters($account_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($account_id)) { $this->Errors("Invalid account"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->account_id=$account_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info($account_id);

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function Info($account_id) {

		$this->db=$GLOBALS['db'];

		$sql="SELECT account_name,industry_id,user_id_assigned,phone_number,fax_number,email_address,account_type_id,
					billing_address,billing_city,billing_state,billing_postal_code,billing_country_id,
					shipping_address,shipping_city,shipping_state,shipping_postal_code,shipping_country_id,
					other_info,
					industry_name,
					account_type_name,
					full_name,
					country_name as billing_country_name,
					country_name as shipping_country_name
					FROM ".$GLOBALS['database_prefix']."v_crm_accounts
					WHERE account_id = '".$account_id."'
					";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				/* HERE WE CALL THE FIELDS AND SET THEM INTO DYNAMIC VARIABLES */
				$i=pg_num_fields($result);
				for ($j=0;$j<$i;$j++) {
					$fieldname=pg_field_name($result,$j);
					$this->$fieldname=$row[pg_field_name($result,$j)];
				}
			}
		}
	}

	public function GetInfo($v) {
		return $this->$v;
	}

	public function Add() {

		/* CHECKS */
		if (!RowExists("visitor_category_master","category_id","'".$this->category_id."'","AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Invalid category."); return False; }
		if (EMPTY($this->date_expected)) { $this->Errors("Expected date must not be empty"); return False; }
		if (EMPTY($this->visitor_identification_number) && EMPTY($this->vehicle_registration_number)) { $this->Errors("Identification number must not be empty"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."visitor_master
					(workspace_id,teamspace_id,vehicle_registration_number,visitor_category_id,date_expected,visitor_full_name,visitor_identification_number,visitor_contact_number,total_guests,
					remarks,user_id,date_added)
					VALUES (
					".$GLOBALS['ui']->WorkspaceID().",
					".$GLOBALS['teamspace_id'].",
					'".$this->vehicle_registration_number."',
					'".$this->category_id."',
					'".EscapeData($this->date_expected)."',
					'".EscapeData($this->visitor_full_name)."',
					'".EscapeData($this->visitor_identification_number)."',
					'".EscapeData($this->visitor_contact_number)."',
					'".EscapeData($this->total_guests)."',
					'".EscapeData($this->remarks)."',
					".$_SESSION['user_id'].",
					now()
					)";
		$result=$db->query($sql);
		if ($result) {
			return True;
		}
		else {
			return False;
		}
	}

	public function GetFormPostedValues() {
		/* THE ARRAY OF POSTED FIELDS */
		$req_fields=array("category_id","date_expected","visitor_full_name","visitor_identification_number","visitor_contact_number","total_guests","vehicle_registration_number","remarks");

		for ($i=0;$i<count($req_fields);$i++) {

			//echo $_POST['application_id'];
			if (ISSET($_POST[$req_fields[$i]]) && !EMPTY($_POST[$req_fields[$i]])) {
				$this->SetVariable($req_fields[$i],$_POST[$req_fields[$i]]);
			}
			else {
				//echo "<br>".$this->req_fields[$i]."<br>";
				$this->$req_fields[$i]="";
			}
		}
	}

	public function SetVariable($variable,$variable_val) {
		$this->$variable=EscapeData($variable_val);
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}
}
?>