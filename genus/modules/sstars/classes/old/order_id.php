<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class OrderID {

	function OrderID($order_id) {

		$db=$GLOBALS['db'];

		$sql="SELECT o.user_id, o.date_order, o.date_collect, o.date_deployed,
					osm.status_name, osm.is_pending_confirm, osm.is_pending_approval, osm.is_ordered, osm.is_acknowledged,
					osm.is_ready_collect, osm.is_deployed, osm.is_out_of_stock, osm.is_deleted
					FROM ".$GLOBALS['database_prefix']."order_master o, ".$GLOBALS['database_prefix']."core_user_master um,
					".$GLOBALS['database_prefix']."order_status_master osm
					WHERE o.order_id = '".$order_id."'
					AND o.user_id = um.user_id
					AND o.status_id = osm.status_id
					ORDER BY o.order_id DESC
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$this->user_id=$row["user_id"];
				$this->username=$row["username"];
				$this->date_order=$row["date_order"];
				$this->date_collect=$row["date_collect"];
				$this->date_deployed=$row["date_deployed"];
				$this->status_name=$row["status_name"];
				$this->is_pending_confirm=$row["is_pending_confirm"];
				$this->is_pending_approval=$row["is_pending_approval"];
				$this->is_ordered=$row["is_ordered"];
				$this->is_acknowledged=$row["is_acknowledged"];
				$this->is_ready_collect=$row["is_ready_collect"];
				$this->is_deployed=$row["is_deployed"];
				$this->is_out_of_stock=$row["is_out_of_stock"];
				$this->is_deleted=$row["is_deleted"];
			}
		}
	}
	function UserID() {	return $this->user_id; }
	function Username() {	return $this->username; }
	function DateOrder() {	return $this->date_order; }
	function DateCollect() {	return $this->date_collect; }
	function DateDeployed() {	return $this->date_deployed; }
	function StatusName() {	return $this->status_name; }
	function IsPendingConfirm() {	return $this->is_pending_confirm; }
	function IsPendingApproval() {	return $this->is_pending_approval; }
	function IsOrdered() {	return $this->is_ordered; }
	function IsAcknowledged() {	return $this->is_acknowledged; }
	function IsReadyCollect() {	return $this->is_ready_collect; }
	function IsDeployed() {	return $this->is_deployed; }
	function IsOutOfStock() {	return $this->is_out_of_stock; }
	function IsDeleted() {	return $this->is_deleted; }
}
?>