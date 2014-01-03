<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function AvailableItems() {
	$db=$GLOBALS['db'];

	$sql="SELECT cm.name as category_name, scm.name as sub_category_name,
				SUM(bi.available_quantity) as available_quantity
				FROM ".$GLOBALS['database_prefix']."batch_items bi, ".$GLOBALS['database_prefix']."category_master cm,
				".$GLOBALS['database_prefix']."sub_category_master scm
				WHERE bi.quantity > 0
				AND bi.category_id = cm.category_id
				AND bi.sub_category_id = scm.sub_category_id
				GROUP BY bi.category_id, bi.sub_category_id
				ORDER BY cm.name, scm.name
				";
	//$c.=$sql;
	$result=$db->Query($sql);
	$c.="<table width='250' class='plain_border'>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2' class='colhead'>Available Stock By Sub Category</td>\n";
		$c.="</tr>\n";
		if ($db->NumRows($result) > 0) {

			$c.="<tr class='colhead'>\n";
				$c.="<td width='40%'>Category</td>\n";
				$c.="<td width='40%'>Sub Category</td>\n";
				$c.="<td width='10%'>Available</td>\n";
			$c.="</tr>\n";
			$total_quantity=0;
			while ($row = $db->FetchArray($result)) {

				$c.="<tr>\n";
					$c.="<td>".$row['category_name']."</a></td>\n";
					$c.="<td>".$row['sub_category_name']."</a></td>\n";
					$c.="<td>".$row['available_quantity']."</a></td>\n";
				$c.="</tr>\n";
			}
		}
		else {
			$c.="<tr>\n";
				$c.="<td colspan='5'>No available stock</td>\n";
			$c.="</tr>\n";
		}
	$c.="</table>\n";

	return $c;
}
?>