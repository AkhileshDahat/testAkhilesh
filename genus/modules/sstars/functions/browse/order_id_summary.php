<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."modules/sstars/classes/order_id.php");
require_once $GLOBALS['dr']."modules/sstars/functions/browse/browse_order_details.php";

function OrderIDSummary($order_id,$show_filter=False) {

	$c="";

	$obj_oi=new OrderID;
	$obj_oi->SetParameters($order_id);

	$c.="<table class='plain'>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2' class='tophead'>Order Details</td>\n";
		$c.="<tr>\n";
		/* CONTENT */
		$c.=ShowTableCell("User",$obj_oi->GetInfo("full_name"));
		$c.=ShowTableCell("Date of order",$obj_oi->GetInfo("date_order"));
	$c.="</table>\n";

	$c.=BrowseOrderDetails($order_id,"",False,"Order Details");

	return $c;
}

function ShowTableCell($cell1,$cell2) {
	$c="<tr>\n";
		$c.="<td>".$cell1."</td>\n";
		$c.="<td>".$cell2."</td>\n";
	$c.="<tr>\n";
	return $c;
}
?>