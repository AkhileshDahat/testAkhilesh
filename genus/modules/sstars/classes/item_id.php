<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class ItemID {

	function __construct() {
		$this->debug=False;
	}

	public function SetParameters($item_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($item_id)) { $this->Errors("Invalid item"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->item_id=$item_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info();

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."sstars_batch_items sbi
					WHERE sbi.item_id = '".$this->item_id."'
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

		if (EMPTY($this->category_id) || !IS_NUMERIC($this->category_id)) { $this->Errors("Invalid category id!"); return False; }
		if (EMPTY($this->sub_category_id) || !IS_NUMERIC($this->sub_category_id)) { $this->Errors("Invalid sub category id!"); return False; }
		if (EMPTY($this->cost_price) || !IS_NUMERIC($this->cost_price)) { $this->Errors("Invalid cost price!"); return False; }
		if (EMPTY($this->quantity) || !IS_NUMERIC($this->quantity)) { $this->Errors("Invalid quantity!"); return False; }

		$this->Debug("Ended Checks for empty fields");

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."sstars_batch_items
					(batch_id,category_id,sub_category_id,cost_price,quantity)
					VALUES (
					'".$this->batch_id."',
					'".$this->category_id."',
					'".$this->sub_category_id."',
					'".$this->cost_price."',
					'".$this->quantity."'
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
		$all_form_fields=array("batch_id","category_id","sub_category_id","cost_price","quantity");

		for ($i=0;$i<count($all_form_fields);$i++) {

			//echo $_POST['item_id'];
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