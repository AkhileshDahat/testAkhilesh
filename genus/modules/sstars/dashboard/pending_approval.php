<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."modules/sstars/functions/orders/approve_order.php";

function PendingApproval($user_id,$summary="yes") {
	$db=$GLOBALS['db'];
	$app_db=$GLOBALS['app_db'];

	/* AS APPROVAL CAN COME FROM MULTIPLE LOCATIONS WE HANDLE IT HERE */
	if ($_POST['approve'] && $_POST['order_id']) {
		$order_id=EscapeData($_POST['order_id']);
		if ($_POST['approve']=="approve") {
			ApproveOrder($order_id);
		}
	}

	$sql="SELECT oa.order_id, om.date_order, um.full_name
				FROM ".$GLOBALS['database_prefix']."order_approval oa, ".$GLOBALS['database_prefix']."order_master om,
				".$GLOBALS['database_prefix']."order_status_master osm, ".$GLOBALS['database_prefix']."core_user_master um
				WHERE oa.user_id = '".$user_id."'
				AND oa.status = 'pending'
				AND oa.order_id = om.order_id
				AND om.user_id = um.user_id
				AND om.status_id = osm.status_id
				AND osm.is_pending_approval = 'y'
				ORDER BY om.date_order
				";
	//$c.=$sql;
	$result=$db->Query($sql);
	$c.="<table width='100%' class='plain_border'>\n";
		$c.="<tr>\n";
			$c.="<td colspan='5' class='colhead'>Orders pending your approval</td>\n";
		$c.="</tr>\n";
		if ($db->NumRows($result) > 0) {

			$c.="<tr class='colhead'>\n";
				$c.="<td width='10%'>#</td>\n";
				$c.="<td width='30%'>Date</td>\n";
				$c.="<td width='40%'>User</td>\n";
				if ($summary=="yes") {
					$c.="<td width='5%'>Approve</td>\n";
					$c.="<td width='5%'>Reject</td>\n";
				}
			$c.="</tr>\n";
			$total_quantity=0;
			while ($row = $db->FetchArray($result)) {

				$c.="<tr onmouseover=\"initpage(".$row['item_id'].");this.className='alternateover'\" onMouseOut=\"this.className=''\">\n";
					$c.="<td><a href='index.php?module=sstars&task=orders&order_id=".$row['order_id']."'>".$row['order_id']."</a></td>\n";
					$c.="<td><a href='index.php?module=sstars&task=orders&order_id=".$row['order_id']."'>".$row['date_order']."</a></td>\n";
					$c.="<td><a href='index.php?module=sstars&task=orders&order_id=".$row['order_id']."'>".$row['full_name']."</a></td>\n";
					if ($summary=="yes") {
						$c.="<form method='post' action='".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."'>\n";
						$c.="<input type='hidden' name='approve' value='approve'>\n";
						$c.="<input type='hidden' name='order_id' value='".$row['order_id']."'>\n";
						$c.="<td width='5%'><input type='image' src='images/buttons/approve.gif' border='0'></td>\n";
						$c.="</form>";
						$c.="<td width='5%'><a href='modules/sstars/bin/orders/approve.php?order_id=".$row['order_id']."'><img src='images/buttons/reject.gif' border='0'></a></td>\n";
					}
				$c.="</tr>\n";
			}
		}
		else {
			$c.="<tr>\n";
				$c.="<td colspan='5'>You have no orders pending your approval</td>\n";
			$c.="</tr>\n";
		}
	$c.="</table>\n";

	return $c;
}
?>