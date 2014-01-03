<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class NewBatch {

	function AddBatch($invoice_number,$description,$cost_price,$purchase_date,$delivery_date,$vendor_id) {

		$this->db=$GLOBALS['db'];

		if (EMPTY($invoice_number)) { $this->BatchErrors("Missing invoice number"); return False; }
		if (EMPTY($cost_price)) { $this->BatchErrors("Missing cost price"); return False; }
		if (EMPTY($vendor_id) || !IS_NUMERIC($vendor_id)) { $this->BatchErrors("Error in Vendor"); return False; }

		if (!$this->InvoiceNumberExists($invoice_number)) {

			$sql="INSERT INTO ".$GLOBALS['database_prefix']."batch
						(invoice_number,description,cost_price,purchase_date,delivery_date,vendor_id)
						VALUES (
						'".$invoice_number."',
						'".$description."',
						'".$cost_price."',
						'".$purchase_date."',
						'".$delivery_date."',
						'".$vendor_id."'
						)";
			//echo $sql."<br>";
			$result = $this->db->Query($sql);
			if ($this->db->AffectedRows($result) > 0) {
				$this->batch_id=$this->db->last_insert_id();
				return True;
			}
			else {
				$this->BatchErrors("Bug");
				return False;
			}
		}
		else {
			$this->BatchErrors("Invoice number exists!");
			return False;
		}
	}

	function EditBatch($batch_id,$description,$cost_price,$purchase_date,$delivery_date,$vendor_id) {

		$this->db=$GLOBALS['db'];

		if ($this->BatchIDExists($batch_id)) {

			$sql="UPDATE ".$GLOBALS['database_prefix']."batch
						SET description = '".$description."',
						cost_price = '".$cost_price."',
						purchase_date = '".$purchase_date."',
						delivery_date = '".$delivery_date."',
						vendor_id = '".$vendor_id."'
						WHERE batch_id = '".$batch_id."'
						";
			//echo $sql."<br>";
			$result = $this->db->Query($sql);
			if ($this->db->AffectedRows($result) > 0) {
				return True;
			}
			else {
				$this->BatchErrors("Bug");
				return False;
			}
		}
		else {
			$this->BatchErrors("Invalid batch!");
			return False;
		}

	}

	function InvoiceNumberExists($invoice_number) {
		$sql="SELECT 'x'
				FROM ".$GLOBALS['database_prefix']."batch
				WHERE invoice_number = '".$invoice_number."'
				";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			return True;
		}
		else {
			return False;
		}
	}

	function BatchIDExists($batch_id) {
		$sql="SELECT 'x'
				FROM ".$GLOBALS['database_prefix']."batch
				WHERE batch_id = '".$batch_id."'
				";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			return True;
		}
		else {
			return False;
		}
	}

	function BatchErrors($err) {
		$this->batch_errors.=$err."<br>";
	}

	function ShowBatchErrors() {
		return $this->batch_errors;
	}

	function BatchID() {
		return $this->batch_id;
	}
}
?>