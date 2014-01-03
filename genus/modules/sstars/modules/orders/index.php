<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."modules/sstars/functions/apply/show_order_details.php";
require_once $dr."modules/sstars/classes/order_id.php";
require_once $dr."modules/sstars/functions/orders/show_status_change.php";

if ($_GET['order_id']) {
	$order_id=EscapeData($_GET['order_id']);
	$oi=new OrderID($order_id);
	echo "<table width='100%' class='plain_border'>\n";
		echo "<tr>\n";
			echo "<td colspan='4' class='colhead'>Order Details</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
			echo "<td>Order #</td>\n";
			echo "<td>".$order_id."</td>\n";
			echo "<td>Order Date</td>\n";
			echo "<td>".$oi->DateOrder()."</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
			echo "<td>Collection Date</td>\n";
			echo "<td>".$oi->DateCollect()."</td>\n";
			echo "<td>Deployed Date</td>\n";
			echo "<td>".$oi->DateDeployed()."</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
			echo "<td>User</td>\n";
			echo "<td>".$oi->Username()."</td>\n";
			echo "<td>Status</td>\n";
			echo "<td>".$oi->StatusName()." ".ShowStatusChange($oi,$order_id)."</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
			echo "<td>Delete</td>\n";
			echo "<td>\n";
			if ($oi->IsDeleted()=="y") {
				echo "Already Deleted";
			}
			else {
				echo "<a href='modules/sstars/bin/order/delete.php?status=is_deleted&order_id=".$order_id."'>Delete</a>\n";
			}
			echo "</td>\n";
			echo "<td>Add to order</td>\n";
			echo "<td>\n";
			if ($oi->IsPendingConfirm()=="y") {
				echo "<a href='index.php?module=sstars&task=apply&order_id=".$order_id."'>Click to add</a>";
			}
			else {
				echo "Not available";
			}
			echo "</td>\n";
		echo "</tr>\n";
	echo "</table>\n";

	echo ShowOrderDetails($order_id);
}
?>