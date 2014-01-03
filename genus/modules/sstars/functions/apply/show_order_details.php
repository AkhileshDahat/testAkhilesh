<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($dr."classes/design/right_click.php");

function ShowOrderDetails($order_id,$show_rc=False) {
	$db=$GLOBALS['db'];
	$app_db=$GLOBALS['app_db'];

	$sql="SELECT od.order_detail_id, od.quantity, cm.name as category_name, scm.name as sub_category_name
				FROM ".$GLOBALS['database_prefix']."order_details od, ".$GLOBALS['database_prefix']."category_master cm,
				".$GLOBALS['database_prefix']."sub_category_master scm
				WHERE od.order_id = '".$order_id."'
				AND od.category_id = cm.category_id
				AND od.sub_category_id = scm.sub_category_id
				ORDER BY cm.name, scm.name
				";
	//$c.=$sql;
	$result=$db->Query($sql);
	$c="<script language='javascript' src='".$GLOBALS["wb"]."include/functions/javascript/right_click/contextmenu.js'></script>\n";
	$c.="<table class='plain_border'>\n";
		$c.="<tr>\n";
			$c.="<td colspan='4'>These are the items in the order</td>\n";
		$c.="</tr>\n";
		if ($db->NumRows($result) > 0) {

			$c.="<tr class='colhead'>\n";
				$c.="<td width='40%'>Category</td>\n";
				$c.="<td width='40%'>Sub Category</td>\n";
				$c.="<td width='5%'>Quantity</td>\n";
			$c.="</tr>\n";
			$total_quantity=0;
			while ($row = $db->FetchArray($result)) {

				/* DRAW THE RIGHT CLICK MENU */
				if ($show_rc) {
					$dm=new DrawMenu;
					$dm->DrawTopMenu($row['order_detail_id']);
					$dm->DrawContent("","","modules/sstars/bin/inventory/delete_item.php?item_id=".$row['item_id']."&batch_id=".$batch_id,"","Delete Item");
					$dm->DrawContent("","","modules/sstars/bin/inventory/duplicate_item.php?item_id=".$row['item_id']."&batch_id=".$batch_id,"","Duplicate Item");
					$dm->DrawBottomMenu();
					$c.=$dm->DrawMenuFinal();
					$init_pg="initpage(".$row['order_detail_id'].");\n";
				}

				$total_quantity+=$row['quantity'];

				$c.="<tr onmouseover=\"".$init_pg."this.className='alternateover'\" onMouseOut=\"this.className=''\">\n";
					$c.="<td>".$row['category_name']."</td>\n";
					$c.="<td>".$row['sub_category_name']."</td>\n";
					$c.="<td align='right'>".$row['quantity']."</td>\n";
				$c.="</tr>\n";
			}
			$c.="<tr class='colfoot'>\n";
				$c.="<td>Total</td>\n";
				$c.="<td></td>\n";
				$c.="<td align='right'>".$total_quantity."</td>\n";
			$c.="</tr>\n";
		}
		else {
			$c.="<tr>\n";
				$c.="<td colspan='4'>No items entered yet</td>\n";
			$c.="</tr>\n";
		}
	$c.="</table>\n";

	return $c;
}
?>