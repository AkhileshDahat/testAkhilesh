<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function BrowseOrders($type="all") {

	$c="";

	$sql_table_joins="";

	/* FILTER THE APPLICATION ID */
	if (ISSET($_POST['order_id'])) { $sql_order_id="AND order_id = ".EscapeData($_POST['order_id']); } else { $sql_order_id=""; }

	/* FILTER THE TYPE */
	if ($type=="my") {
		$sql_table_joins="AND om.user_id = ".$_SESSION['user_id'];
	}
	else {
		$sql_table_joins="";
	}

	$sr=new ShowResults;
	$sr->SetParameters(True);

	$sr->DrawFriendlyColHead(array("ID","User Name","Date",)); /* COLS */
	$sr->Columns(array("order_id","full_name","date_order"));

	$sql="SELECT om.order_id,cm.full_name,om.date_order
				FROM ".$GLOBALS['database_prefix']."sstars_order_master om, ".$GLOBALS['database_prefix']."core_user_master cm
				WHERE om.workspace_id = ".$GLOBALS['workspace_id']."
				AND om.teamspace_id ".$GLOBALS['teamspace_sql']."
				$sql_table_joins
				$sql_order_id
				AND om.user_id = cm.user_id
				ORDER BY order_id DESC
							";
	//echo $sql;
	$sr->Query($sql);
	for ($i=0;$i<$sr->CountRows();$i++) {
		$order_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */

		/* THIS IS THE LINK TO DETAILS */
		$sr->ModifyData($i,0,"<a href='index.php?module=sstars&task=order_details&order_id=".$order_id."' title='Click to go'>".$order_id."</a>");
	}
	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Browse Orders");

	/* THE FILTER BLOCK */
	if (ISSET($_POST['order_id'])) { $order_id=EscapeData($_POST['order_id']); } else { $order_id=""; }
	$sr->TableFilter("<div align='right'>Order#<input type='text' name='order_id' value='".$order_id."' size=4></div>","<form name='batch_id_filter' method='post' action='index.php?module=sstars&task=".EscapeData($_GET['task'])."'>","</form>");


	$sr->Footer();

	$c.=$sr->Draw();

	/* PLACE THE FOCUS ON THE application ID FILTER */
	$c.="<script language='JavaScript'>\n";
	$c.="document.batch_id_filter.batch_id.focus();\n";
	$c.="</script>\n";

	return $c;
}
?>