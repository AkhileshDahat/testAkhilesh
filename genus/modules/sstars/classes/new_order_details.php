<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."include/functions/db/get_col_value.php";

class NewOrderDetails {

	function AddOrderMaster($user_id) {

		$this->db=$GLOBALS['db'];

		$status_id=GetColumnValue("status_id","order_status_master","is_pending_confirm","y","");

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."order_master
					(user_id,date_order,status_id)
					VALUES (
					'".$user_id."',
					sysdate(),
					'".$status_id."'
					)";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->AffectedRows($result) > 0) {
			$this->order_id=$this->db->last_insert_id();
			$this->GetSuperiorApproval($user_id);
			$this->GetGlobalApproval($user_id);
			return True;
		}
		else {
			$this->OrderDetailsErrors("Bug");
			return False;
		}
	}

	function AddOrderDetails($order_id,$category_id,$sub_category_id,$quantity,$user_id) {

		$this->db=$GLOBALS['db'];
		/* THIS HAPPENS ON THE FIRST ITEM ORDERED - WHERE THERE IS NO MASTER ORDER SO WE CREATE ONE */
		if (EMPTY($order_id)) {
			$this->AddOrderMaster($user_id);
			$order_id=$this->order_id;
		}

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."order_details
					(order_id,category_id,sub_category_id,quantity)
					VALUES (
					'".$order_id."',
					'".$category_id."',
					'".$sub_category_id."',
					'".$quantity."'
					)";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->AffectedRows($result) > 0) {
			$this->order_detail_id=$this->db->last_insert_id();
			$available_quantity=$this->CheckSubCatAvailBalance($category_id,$sub_category_id);
			//echo $quantity."<br>";
			//echo $available_quantity."<br>";
			if ($available_quantity > $quantity) {
				//echo "Ok balance sufficient in the category<br>";
				if ($this->OrderDetailsItems($order_id,$this->order_detail_id,$category_id,$sub_category_id,$quantity)) {
					return True;
				}
				else {
					$this->OrderDetailsErrors("Bug");
					return False;
				}
			}
			else {
				$this->OrderDetailsErrors("Insufficient items in the category and sub category chosen.");
				return False;
			}

		}
		else {
			$this->OrderDetailsErrors("Bug");
			return False;
		}
	}

	function OrderIDExists($order_id) {
		$sql="SELECT 'x'
				FROM ".$GLOBALS['database_prefix']."order_master
				WHERE order_id = '".$order_id."'
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

	function GetSuperiorApproval($user_id) {
		$sql="SELECT superior_user_id
				FROM ".$GLOBALS['database_prefix']."core_user_master
				WHERE user_id = '".$user_id."'
				";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				$this->InsertApproval($row['superior_user_id']);
			}
		}
	}

	function GetGlobalApproval() {
		$sql="SELECT user_id
					FROM ".$GLOBALS['database_prefix']."global_approval
					";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				$this->InsertApproval($row['user_id']);
			}
		}
	}

	function InsertApproval($user_id) {
		$sql="INSERT INTO ".$GLOBALS['database_prefix']."order_approval
					(order_id,user_id,status)
					VALUES (
					'".$this->order_id."',
					'".$user_id."',
					'pending'
					)
					";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
	}

	function CheckSubCatAvailBalance($category_id,$sub_category_id) {
		//$sql="set autocommit=1";
		//$this->db->Query($sql);
		$sql="SELECT SUM(available_quantity) as available_quantity
					FROM ".$GLOBALS['database_prefix']."batch_items
					WHERE category_id = '".$category_id."'
					AND sub_category_id = '".$sub_category_id."'
					";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				//echo "AQ:".$row['available_quantity']."<br>";
				return $row['available_quantity'];
			}
		}
	}

	function OrderDetailsItems($order_id,$order_detail_id,$category_id,$sub_category_id,$par_quantity) {

		$success=True; /* SET THE DEFAULT SINCE WE CANNOT ROLLBACK IN THE MIDDLE OF THIS */
		$req_quantity=$par_quantity; /* SET THE QUANTITY INTO A REQUIRED AMOUNT */
		$used_quantity=0; /* THIS IS THE AMOUNT WE HAVE USED AS WE GO ALONG */

		/* LOCK THE TABLE SO THAT THERE ARE NO OTHER WRITES */
		//$sql="LOCK TABLE ".$GLOBALS['database_prefix']."batch_items WRITE";
		//$this->db->Query($sql);


		/* GO ABOUT SUBTRACTING ITEMS */
		$sql="SELECT item_id,available_quantity
					FROM ".$GLOBALS['database_prefix']."batch_items
					WHERE category_id = '".$category_id."'
					AND sub_category_id = '".$sub_category_id."'
					";
		//echo $sql."<br>";

		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				/* THIS HAPPENS WHEN THERE ARE MORE ITEMS IN STOCK THAN THE ORDER REQUIRES */
				//echo "Quantity in this category:".$row['available_quantity']."<br>";
				//echo "Quantity required:".$quantity."<br>";
				/*
					CHECK IF THE QUANTITY ORDERED IS LESS THAN THE BALANCE FOR THIS SUB-CATEGORY
				*/
				if ($row['available_quantity'] > $req_quantity) {
					//echo "Ok sufficient amount left<br>";
					/*
						DEDUCT FROM STOCK
					*/
					if ($this->DeductBatchItems($row['item_id'],$req_quantity)) {
						//echo "Deducted stock successfully.<br>";
						if ($this->InsertOrderDetailsItems($order_detail_id,$row['item_id'],$req_quantity)) {
							//echo "Detailed order captured<br>";
						}
						$quantity=0;
					}
					/*
						POSSIBLE BUG
					*/
					else {
						$this->OrderDetailsErrors("Bug in deducting stock");
						return False;
					}
				}
				/*
					THE QUANTITY ORDERED IS MORE THAN THE BALANCE FOR THIS SUB-CATEGORY
				*/
				else {
					//echo "Insufficient quantity in single category, deducting from multiple categories<br>";
					$req_quantity=($req_quantity-$row['available_quantity']);
					if ($this->DeductBatchItems($row['item_id'],$row['available_quantity'])) { /* DEDUCT WHATEVE WE CAN FROM THE CATEGORY */
						//echo "Deduting from stock pool<br>";
						if ($this->InsertOrderDetailsItems($order_detail_id,$row['item_id'],$row['available_quantity'])) {
							//echo "Inserting the detail order detail breakdown<br>";
						}
						else {
							$this->OrderDetailsErrors("Possible bug in recording the detail order");
							return False;
						}
					}
				}
			}
		}

		/* UNLOCK TABLES */
		//$sql="UNLOCK TABLES";
		//$this->db->Query($sql);
		return $success;
	}

	function DeductBatchItems($item_id,$quantity) {

		$db=$GLOBALS['db'];
		$sql="UPDATE ".$GLOBALS['database_prefix']."batch_items
					SET available_quantity = available_quantity - ".$quantity.",
					pending_quantity = pending_quantity + ".$quantity."
					WHERE item_id = '".$item_id."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			return False;
		}
	}

	function InsertOrderDetailsItems($order_detail_id,$item_id,$quantity) {

		$db=$GLOBALS['db'];
		$sql="INSERT INTO ".$GLOBALS['database_prefix']."order_details_items
					(order_detail_id,item_id,quantity)
					VALUES (
					'".$order_detail_id."',
					'".$item_id."',
					'".$quantity."'
					)";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			return False;
		}
	}

	function GetOrderDetailID() {
		return $this->order_detail_id;
	}

	function OrderDetailsErrors($err) {
		$this->order_details_errors.=$err."<br>";
	}

	function ShowOrderDetailsErrors() {
		return $this->order_details_errors;
	}

	function OrderDetailID() {
		return $this->order_detail_id;
	}

	function OrderID() {
		return $this->order_id;
	}
}
?>