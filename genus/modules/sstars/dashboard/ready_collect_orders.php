<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function ReadyCollectOrders($user_id) {
	$db=$GLOBALS['db'];
	$app_db=$GLOBALS['app_db'];

	$sql="SELECT om.order_id, om.date_collect
				FROM ".$GLOBALS['database_prefix']."order_master om, ".$GLOBALS['database_prefix']."order_status_master osm
				WHERE om.user_id = '".$user_id."'
				AND om.status_id = osm.status_id
				AND osm.is_ready_collect = 'y'
				ORDER BY om.date_order
				";
	//$c.=$sql;
	$result=$db->Query($sql);
	$c.="<table width='200' class='plain_border'>\n";
		$c.="<tr>\n";
			$c.="<td colspan='4' class='colhead'>Please collect your order</td>\n";
		$c.="</tr>\n";
		if ($db->NumRows($result) > 0) {

			$c.="<tr class='colhead'\n";
				$c.="<td width='10%'>#</td>\n";
				$c.="<td width='40%'>Collection Date</td>\n";
			$c.="</tr>\n";
			$total_quantity=0;
			while ($row = $db->FetchArray($result)) {

				$c.="<tr onmouseover=\"initpage(".$row['item_id'].");this.className='alternateover'\" onMouseOut=\"this.className=''\">\n";
					$c.="<td><a href='index.php?module=sstars&task=orders&order_id=".$row['order_id']."'>".$row['order_id']."</a></td>\n";
					$c.="<td>".$row['date_collect']."</td>\n";
				$c.="</tr>\n";
			}
		}
	$c.="</table>\n";

	return $c;
}
?>