<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");
require_once($GLOBALS['dr']."modules/projects/classes/category_id.php");

function ShowProjects($p_filter="") {

	$c="";

	$sql_table_joins="";

	/* FILTER VARIOUS VIEWS OF ALL THE TICKETS */
	if ($p_filter=="my") { $sql_extra="AND pm.user_id_logging = ".$_SESSION['user_id']; }
	//elseif ($p_filter=="delegated") { $sql_table_joins=", ".$GLOBALS['database_prefix']."projects_ticket_delegation htd"; $sql_extra="AND pm.project_id = htd.project_id AND htd.user_id = ".$_SESSION['user_id']; }
	else { $sql_extra=""; }

	/* FILTER THE TICKET ID */
	if (ISSET($_POST['project_id']) && IS_NUMERIC($_POST['project_id'])) { $sql_project_id="AND pm.project_id = ".EscapeData($_POST['project_id']); } else { $sql_project_id=""; }

	$sr=new ShowResults;
	$sr->SetParameters(True);

	$sr->DrawFriendlyColHead(array("ID","Title")); /* COLS */
	$sr->Columns(array("project_id","title"));
	$sql="SELECT pm.project_id, pm.title
							FROM ".$GLOBALS['database_prefix']."project_master pm
							$sql_table_joins
							WHERE workspace_id = ".$GLOBALS['workspace_id']."
							AND teamspace_id ".$GLOBALS['teamspace_sql']."
							$sql_extra
							$sql_project_id
							ORDER BY project_id DESC
							";
	//echo $sql;
	$sr->Query($sql);
	for ($i=0;$i<$sr->CountRows();$i++) {
		$project_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */

		/* THIS IS THE LINK TO DETAILS */
		$sr->ModifyData($i,0,"<a href='index.php?module=projects&task=browse&project_id=".$project_id."' title='Click to go'>".$project_id."</a>");
	}
	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Browse Projects");

	/* THE FILTER BLOCK */
	if (ISSET($_POST['project_id'])) { $project_id=EscapeData($_POST['project_id']); } else { $project_id=""; }
	$sr->TableFilter("<div align='right'>Project ID Filter<input type='text' name='project_id' value='".$project_id."' size=4></div>","<form name='project_id_filter' method='post' action='index.php?module=projects&task=browse'>","</form>");


	$sr->Footer();

	$c.=$sr->Draw();

	/* PLACE THE FOCUS ON THE TICKET ID FILTER */
	$c.="<script language='JavaScript'>\n";
	$c.="document.project_id_filter.project_id.focus();\n";
	$c.="</script>\n";

	return $c;
}
?>