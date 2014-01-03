<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");
require_once($GLOBALS['dr']."modules/projects/classes/category_id.php");

function DashboardShowProjects() {

	$c="";

	$sr=new ShowResults;
	$sr->SetParameters(True);

	$sr->DrawFriendlyColHead(array("","Title")); /* COLS */
	$sr->Columns(array("project_id","title"));
	$sql="SELECT pm.project_id, pm.title
							FROM ".$GLOBALS['database_prefix']."project_master pm
							WHERE workspace_id = ".$GLOBALS['workspace_id']."
							AND teamspace_id ".$GLOBALS['teamspace_sql']."
							ORDER BY project_id DESC
							";
	//echo $sql;
	$sr->Query($sql);

	for ($i=0;$i<$sr->CountRows();$i++) {
		$project_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$title=$sr->GetRowVal($i,1); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */

		/* THIS IS THE LINK TO DETAILS */
		$sr->ModifyData($i,1,"<a href='index.php?module=projects&task=details&project_id=".$project_id."' title='Click to go'>".$title."</a>");
	}
	$sr->RemoveColumn(0);
	$sr->WrapData();
	$sr->TableTitle("","Browse Projects");
	//$sr->Footer();
	$c.=$sr->Draw();

	return $c;
}
?>