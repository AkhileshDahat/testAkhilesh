<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function ShowStatusChange($oi,$order_id) {
	/*
	$c="<form method='post' action='modules/sstars/bin/orders/change_status.php'>\n";
	$c.="<select name='status_name'>\n";
	if ($oi->IsOrdered()=="y") {
		$c.="<option value='ack'>Acknowledged</option>";
		$c.="<option value='ready'>Ready</option>";
		$c.="<option value='del'>Deleted</option>";
	}

	$c.="</select>\n";
	*/
	if ($oi->IsOrdered()=="y") {
		$c.="| <a href='modules/sstars/bin/order/change_status_from_name.php?order_id=".$order_id."&status_name=ack'>Ack</a>\n";
		$c.="| <a href='modules/sstars/bin/order/change_status_from_name.php?order_id=".$order_id."&status_name=ready'>Ready</a>\n";
		$c.="| <a href='modules/sstars/bin/order/change_status_from_name.php?order_id=".$order_id."&status_name=del'>Del</a>\n";
	}
	elseif ($oi->IsAcknowledged()=="y") {
		$c.="| <a href='modules/sstars/bin/order/change_status_from_name.php?order_id=".$order_id."&status_name=ready'>Ready</a>\n";
		$c.="| <a href='modules/sstars/bin/order/change_status_from_name.php?order_id=".$order_id."&status_name=del'>Del</a>\n";
	}
	elseif ($oi->IsReadyCollect()=="y") {
		$c.="| <a href='modules/sstars/bin/order/change_status_from_name.php?order_id=".$order_id."&status_name=deployed'>Collected</a>\n";
	}


	return $c;
}

?>