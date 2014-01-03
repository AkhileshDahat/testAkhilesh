<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."modules/sstars/functions/orders/confirm_order.php";

function UnconfirmedOrders($user_id) {
	$db=$GLOBALS['db'];
	$app_db=$GLOBALS['app_db'];

	/* THIS ALLOWS US TO CONFIRM WHEREVER THIS INCLUDE FILE IS */
	if ($_GET['order_id'] && $_GET['confirm']=="y") {
		ConfirmOrder(EscapeData($_GET['order_id']));
	}

	$sql="SELECT om.order_id, om.date_order
				FROM ".$GLOBALS['database_prefix']."order_master om, ".$GLOBALS['database_prefix']."order_status_master osm
				WHERE om.user_id = '".$user_id."'
				AND om.status_id = osm.status_id
				AND osm.is_pending_confirm = 'y'
				ORDER BY om.order_id DESC
				";
	//$c.=$sql;
	$result=$db->Query($sql);
	$c.="<table width='250' class='plain_border'>\n";
		$c.="<tr>\n";
			$c.="<td colspan='4' class='colhead'>Unconfirmed orders</td>\n";
		$c.="</tr>\n";
		if ($db->NumRows($result) > 0) {

			$c.="<tr class='colhead'>\n";
				$c.="<td width='10%'>#</td>\n";
				$c.="<td width='60%'>Date</td>\n";
				$c.="<td width='25%'>Confirm</td>\n";
			$c.="</tr>\n";
			$total_quantity=0;
			while ($row = $db->FetchArray($result)) {

				$c.="<tr onmouseover=\"initpage(".$row['item_id'].");this.className='alternateover'\" onMouseOut=\"this.className=''\">\n";
					$c.="<td><a href='index.php?module=sstars&task=orders&order_id=".$row['order_id']."'>".$row['order_id']."</a></td>\n";
					$c.="<td><a href='index.php?module=sstars&task=orders&order_id=".$row['order_id']."'>".$row['date_order']."</a></td>\n";
					$c.="<td><a href='".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."&order_id=".$row['order_id']."&confirm=y'>Confirm</a></td>\n";
				$c.="</tr>\n";
			}
		}
	$c.="</table>\n";

	return $c;
}
?>