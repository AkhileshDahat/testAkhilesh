<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($dr."classes/design/right_click.php");


function ShowBatchItems($batch_id) {
	$db=$GLOBALS['db'];
	$app_db=$GLOBALS['app_db'];

	$sql="SELECT bi.item_id, format(bi.quantity,0) as quantity_format, bi.quantity, bi.cost_price, cm.name as category_name, scm.name as sub_category_name
				FROM sstars_batch_items bi, sstars_category_master cm,
				sstars_sub_category_master scm
				WHERE batch_id = '".$batch_id."'
				AND bi.category_id = cm.category_id
				AND bi.sub_category_id = scm.sub_category_id
				ORDER BY cm.name, scm.name
				";
	//$c.=$sql;
	$result=$db->Query($sql);
	$c="<script language='javascript' src='".$GLOBALS["wb"]."include/functions/javascript/right_click/contextmenu.js'></script>\n";
	$c.="<table width='600' class='plain_border'>\n";
		$c.="<tr>\n";
			$c.="<td colspan='4' class='colhead'>These are the items in the batch</td>\n";
		$c.="</tr>\n";
		if ($db->NumRows($result) > 0) {

			$c.="<tr class='colhead'>\n";
				$c.="<td width='40%'>Category</td>\n";
				$c.="<td width='40%'>Sub Category</td>\n";
				$c.="<td width='5%'>Cost</td>\n";
				$c.="<td width='5%'>Quantity</td>\n";
			$c.="</tr>\n";
			$total_cost_price=0;
			$total_quantity=0;
			while ($row = $db->FetchArray($result)) {

				/* DRAW THE RIGHT CLICK MENU */
				$dm=new DrawMenu;
				$dm->DrawTopMenu($row['item_id']);
				//$dm->DrawContent("contextmenuhead","","","",$heading);
				$dm->DrawContent("","","modules/sstars/bin/inventory/delete_item.php?item_id=".$row['item_id']."&batch_id=".$batch_id,"","Delete Item");
				$dm->DrawContent("","","modules/sstars/bin/inventory/duplicate_item.php?item_id=".$row['item_id']."&batch_id=".$batch_id,"","Duplicate Item");
				$dm->DrawBottomMenu();
				$c.=$dm->DrawMenuFinal();

				$total_cost_price+=$row['cost_price'];
				$total_quantity+=$row['quantity'];


				$c.="<tr onmouseover=\"initpage(".$row['item_id'].");this.className='alternateover'\" onMouseOut=\"this.className=''\">\n";
					$c.="<td>".$row['category_name']."</td>\n";
					$c.="<td>".$row['sub_category_name']."</td>\n";
					$c.="<td align='right'>".$row['cost_price']."</td>\n";
					$c.="<td align='right'>".$row['quantity_format']."</td>\n";
				$c.="</tr>\n";
			}
			$c.="<tr class='colfoot'>\n";
				$c.="<td>Total</td>\n";
				$c.="<td></td>\n";
				$c.="<td align='right'>".NUMBER_FORMAT($total_cost_price)."</td>\n";
				$c.="<td align='right'>".NUMBER_FORMAT($total_quantity)."</td>\n";
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