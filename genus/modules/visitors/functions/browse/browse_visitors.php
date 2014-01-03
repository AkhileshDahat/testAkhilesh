<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function BrowseVisitors($p_filter="") {

	$c="";

	$sql_table_joins="";

	/* FILTER VARIOUS VIEWS OF ALL THE APPLICATIONS */
	if ($p_filter=="my") { $sql_extra="AND la.user_id = ".$_SESSION['user_id']; }
	else { $sql_extra=""; }

	/* FILTER THE APPLICATION ID */
	//if (ISSET($_POST['application_id']) && IS_NUMERIC($_POST['application_id'])) { $sql_application_id="AND la.application_id = ".EscapeData($_POST['application_id']); } else { $sql_application_id=""; }

	$sr=new ShowResults;
	$sr->SetParameters(True);

	$sr->DrawFriendlyColHead(array("","Name","Registration","ID","Date")); /* COLS */
	$sr->Columns(array("visitor_id","visitor_full_name","vehicle_registration_number","visitor_identification_number","date_expected"));

	$sql="SELECT visitor_id,visitor_full_name,vehicle_registration_number,visitor_identification_number,date_expected
							FROM ".$GLOBALS['database_prefix']."visitor_master vm
							$sql_table_joins
							WHERE date_expected >= date_add(now(), INTERVAL -1 DAY)
							AND workspace_id = ".$GLOBALS['workspace_id']."
							AND teamspace_id ".$GLOBALS['teamspace_sql']."
							$sql_extra
							ORDER BY visitor_id DESC
							";
	//echo $sql;
	$sr->Query($sql);
	for ($i=0;$i<$sr->CountRows();$i++) {
		$visitor_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */

		/* THIS IS THE LINK TO DETAILS */
		//$sr->ModifyData($i,0,"<a href='index.php?module=leave&task=application_details&application_id=".$application_id."' title='Click to go'>".$application_id."</a>");
	}
	$sr->RemoveColumn(0);
	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Browse Visitors");

	/* THE FILTER BLOCK */
	//if (ISSET($_POST['application_id'])) { $application_id=EscapeData($_POST['application_id']); } else { $application_id=""; }
	//$sr->TableFilter("<div align='right'>application ID Filter<input type='text' name='application_id' value='".$application_id."' size=4></div>","<form name='application_id_filter' method='post' action='index.php?module=leave&task=".EscapeData($_GET['task'])."'>","</form>");


	$sr->Footer();

	$c.=$sr->Draw();

	/* PLACE THE FOCUS ON THE application ID FILTER */
	$c.="<script language='JavaScript'>\n";
	$c.="document.application_id_filter.application_id.focus();\n";
	$c.="</script>\n";

	return $c;
}
?>