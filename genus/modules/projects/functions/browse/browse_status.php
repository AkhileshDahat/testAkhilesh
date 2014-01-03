<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function BrowseStatus() {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Status Name","Edit","Delete")); /* COLS */
	$sr->Columns(array("status_id","status_name","edit","del"));
	$sr->Query("SELECT status_id,status_name,'edit' AS edit,'delete' AS del
							FROM ".$GLOBALS['database_prefix']."project_status_master
							WHERE workspace_id = ".$GLOBALS['workspace_id']."
							AND teamspace_id ".$GLOBALS['teamspace_sql']."
							ORDER BY status_name");

	for ($i=0;$i<$sr->CountRows();$i++) {
		$status_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$sr->ModifyData($i,2,"<a href='index.php?module=projects&task=status&subtask=edit&status_id=".$status_id."' title='Edit'>Edit</a>");
		$sr->ModifyData($i,3,"<a href='index.php?module=projects&task=status&subtask=delete&status_id=".$status_id."' title='Delete'>Delete</a>");
	}
	$sr->RemoveColumn(0);

	$sr->WrapData();
	$sr->Footer();
	$sr->TableTitle("nuvola/32x32/apps/kuser.png","Browsing status");
	return $sr->Draw();

}

?>