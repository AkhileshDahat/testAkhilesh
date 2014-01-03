<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class OrderID {

	function __construct() {

	}
	public function SetParameters($order_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($order_id)) { $this->Errors("Invalid order"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->order_id=$order_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info($order_id);

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	private function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT om.order_id,cm.full_name,om.date_order
				FROM ".$GLOBALS['database_prefix']."sstars_order_master om, ".$GLOBALS['database_prefix']."core_user_master cm
				WHERE om.workspace_id = ".$GLOBALS['workspace_id']."
				AND om.teamspace_id ".$GLOBALS['teamspace_sql']."
				AND om.user_id = cm.user_id
				AND order_id = '".$this->order_id."'
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
		return $this->$v;
	}

	public function AddMaster() {

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."sstars_order_master
					(user_id,date_order,workspace_id,teamspace_id)
					VALUES (
					'".$_SESSION['user_id']."',
					now(),
					".$GLOBALS['workspace_id'].",
					".$GLOBALS['teamspace_id']."
					)";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				//LogHistory($category_name." added");
				$this->order_id=$db->LastInsertID();
				return True;
		}
		else {
			return False;
		}
	}

	public function AddDetails($order_id,$category_id,$sub_category_id,$quantity) {

		/* CHECKS */
		if (!RowExists("sstars_order_master","order_id","'".$order_id."'","AND user_id=".$_SESSION['user_id'])) { $this->Errors("Invalid order id. Please restart the order."); return False; }
		if (!RowExists("sstars_category_master","category_id","'".$category_id."'","AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Category name exists. Please choose another."); return False; }
		if (!RowExists("sstars_sub_category_master","sub_category_id","'".$sub_category_id."'","AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Category name exists. Please choose another."); return False; }
		if(!IS_NUMERIC($quantity))  { $this->Errors("Invalid quantity. Please use only numeric values!"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."sstars_order_details
					(order_id,category_id,sub_category_id,quantity)
					VALUES (
					'".$order_id."',
					'".$category_id."',
					'".$sub_category_id."',
					'".$quantity."'
					)";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				//LogHistory($category_name." added");
				return True;
		}
		else {
			return False;
		}
	}

	public function Edit($category_name) {

		/* CHECKS */
		if (!$this->parameter_check) { $this->Errors("Parameter check failed"); return False; }
		if(!preg_match("([a-zA-Z0-9])",$category_name))  { $this->Errors("Invalid category name. Please use only alpha-numeric values!"); return False; }

		//if (RowExists("sstars_category_master","category_name","'".$category_name."'","AND workspace_id=".$GLOBALS['workspace_id'])) { $this->Errors("Category name exists. Please choose another."); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="UPDATE ".$GLOBALS['database_prefix']."sstars_category_master
					SET category_name = '".$category_name."'
					WHERE order_id = ".$this->order_id."
					AND workspace_id = ".$GLOBALS['workspace_id']."
					AND teamspace_id ".$GLOBALS['teamspace_sql']."
					";
		//echo $sql."<br>";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				return True;
		}
		else {
			return False;
		}
	}

	public function Delete() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			$sql="DELETE
						FROM ".$GLOBALS['database_prefix']."sstars_category_master
						WHERE order_id = ".$this->order_id."
						AND workspace_id = ".$GLOBALS['workspace_id']."
						AND teamspace_id ".$GLOBALS['teamspace_sql']."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				LogHistory($this->category_name." deleted");
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

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}
}
?>