<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."include/functions/db/get_col_value.php";

class DeleteOrder {

	function ExecDeleteOrderDetails($order_id) {

		$this->db=$GLOBALS['db']; /* SET THE DATABASE CONNECTION TO BE AVAILAbLE THROUGHOUT THE CLASS */

		$sql="SELECT odi.item_id, odi.quantity
					FROM ".$GLOBALS['database_prefix']."order_details od, ".$GLOBALS['database_prefix']."order_details_items odi
					WHERE od.order_id = '".$order_id."'
					AND od.order_detail_id = odi.order_detail_id
					";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				$sql="UPDATE ".$GLOBALS['database_prefix']."batch_items bi
							SET available_quantity = available_quantity + ".$row['quantity']."
							WHERE item_id = '".$row['item_id']."'
							";
				echo $sql;
				$result_1 = $this->db->Query($sql);
				if ($this->db->AffectedRows($result_1) == 0) { return False; }
			}
		}
		/* DELETE THE ORDER MASTER */
		$this->DeleteOrderMaster($order_id);
		/* DELETE THE ITEMS ORDERED */
		$this->DeleteOrderDetails($order_id);
		/* DELETE THE ITEMS DETAILS */
		$this->DeleteOrderDetailsItems($order_id);
	}

	function DeleteOrderMaster($order_id) {
		$sql="DELETE FROM ".$GLOBALS['database_prefix']."order_master
					WHERE order_id = '".$order_id."'
					";
		$result = $this->db->Query($sql);
		if ($this->db->AffectedRows($result) == 0) {
			return False;
		}
	}

	function DeleteOrderDetails($order_id) {
		$sql="DELETE FROM ".$GLOBALS['database_prefix']."order_details
					WHERE order_id = '".$order_id."'
					";
		$result = $this->db->Query($sql);
		if ($this->db->AffectedRows($result) == 0) {
			return False;
		}
	}

	function DeleteOrderDetailsItems($order_id) {
		$sql="DELETE ".$GLOBALS['database_prefix']."order_details_items
					FROM ".$GLOBALS['database_prefix']."order_details od, ".$GLOBALS['database_prefix']."order_details_items odi
					WHERE od.order_id = '".$order_id."'
					AND od.order_detail_id = odi.order_detail_id
					";
		$result = $this->db->Query($sql);
		if ($this->db->AffectedRows($result) == 0) {
			return False;
		}
	}

	function Errors($err) {
		$this->order_details_errors.=$err."<br>";
	}

	function ShowErrors() {
		return $this->order_details_errors;
	}
}
?>