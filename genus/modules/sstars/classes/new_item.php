<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class NewItem {

	function AddItem($batch_id,$category_id,$sub_category_id,$quantity,$cost_price) {

		$this->db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."batch_items
					(batch_id,category_id,sub_category_id,quantity,cost_price,available_quantity)
					VALUES (
					'".$batch_id."',
					'".$category_id."',
					'".$sub_category_id."',
					'".$quantity."',
					'".$cost_price."',
					'".$quantity."'
					)";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->AffectedRows($result) > 0) {
			$this->item_id=$this->db->last_insert_id();
			return True;
		}
		else {
			$this->ItemErrors("Bug");
			return False;
		}
	}

	function ItemErrors($err) {
		$this->item_errors.=$err."<br>";
	}

	function ShowItemErrors() {
		return $this->item_errors;
	}

	function ItemID() {
		return $this->item_id;
	}
}
?>