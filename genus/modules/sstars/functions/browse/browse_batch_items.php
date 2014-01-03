<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function BrowseBatchItems($batch_id,$show_filter=False) {

	$c="";

	$sql_table_joins="";

	/* FILTER THE APPLICATION ID */
	if (ISSET($_POST['invoice_number'])) { $sql_invoice_number="AND invoice_number = ".EscapeData($_POST['invoice_number']); } else { $sql_invoice_number=""; }

	$sr=new ShowResults;
	$sr->SetParameters(True);

	$sr->DrawFriendlyColHead(array("Category","Sub Category","Quantity","Cost Price")); /* COLS */
	$sr->Columns(array("category_name","sub_category_name","quantity","cost_price"));

	$sql="SELECT category_name,sub_category_name,quantity,cost_price
				FROM ".$GLOBALS['database_prefix']."sstars_batch_items bi, ".$GLOBALS['database_prefix']."sstars_category_master cm, ".$GLOBALS['database_prefix']."sstars_sub_category_master scm
				WHERE bi.batch_id = '".$batch_id."'
				AND bi.category_id = cm.category_id
				AND bi.sub_category_id = scm.sub_category_id
				ORDER BY cost_price
				";
	//echo $sql;
	$sr->Query($sql);
	//for ($i=0;$i<$sr->CountRows();$i++) {
		//$batch_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */

		/* THIS IS THE LINK TO DETAILS */
		//$sr->ModifyData($i,0,"<a href='index.php?module=sstars&task=batch_items&batch_id=".$batch_id."' title='Click to go'>".$batch_id."</a>");
	//}
	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Browse Batch Items");

	/* THE FILTER BLOCK */
	if ($show_filter) {
		if (ISSET($_POST['invoice_number'])) { $invoice_number=EscapeData($_POST['invoice_number']); } else { $invoice_number=""; }
		$sr->TableFilter("<div align='right'>Invoice#<input type='text' name='invoice_number' value='".$invoice_number."' size=4></div>","<form name='batch_id_filter' method='post' action='index.php?module=sstars&task=".EscapeData($_GET['task'])."'>","</form>");
	}


	$sr->Footer();

	$c.=$sr->Draw();

	/* PLACE THE FOCUS ON THE application ID FILTER */
	$c.="<script language='JavaScript'>\n";
	$c.="document.batch_id_filter.batch_id.focus();\n";
	$c.="</script>\n";

	return $c;
}
?>