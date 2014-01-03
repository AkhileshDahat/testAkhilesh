<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function BrowseOrderDetails($order_id,$show_filter=False,$show_delete=True,$heading="Items in your order") {

	$c="";

	$sql_table_joins="";

	$sr=new ShowResults;
	$sr->SetParameters(True);

	$sr->DrawFriendlyColHead(array("","Category","Sub Category","Quantity","Delete")); /* COLS */
	$sr->Columns(array("order_item_id","category_name","sub_category_name","quantity","del"));

	$sql="SELECT od.order_item_id,cm.category_name,scm.sub_category_name,od.quantity,'del' as del
				FROM ".$GLOBALS['database_prefix']."sstars_order_details od, ".$GLOBALS['database_prefix']."sstars_category_master cm, ".$GLOBALS['database_prefix']."sstars_sub_category_master scm
				WHERE od.order_id = '".$order_id."'
				AND od.category_id = cm.category_id
				AND od.sub_category_id = scm.sub_category_id
				ORDER BY cm.category_name, scm.sub_category_id
				";
	//echo $sql;
	$sr->Query($sql);
	if ($show_delete) {
		for ($i=0;$i<$sr->CountRows();$i++) {
			$order_item_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
			/* THIS IS THE LINK TO DELETE */
			$sr->ModifyData($i,4,"<a href='index.php?module=sstars&task=order&order_item_id=".$order_item_id."' title='Click to go'>Delete</a>");
		}
	}

	$sr->RemoveColumn(0);
	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png",$heading);

	$sr->Footer();

	$c.=$sr->Draw();

	return $c;
}
?>