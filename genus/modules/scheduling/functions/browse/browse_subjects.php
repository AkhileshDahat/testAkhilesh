<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function BrowseSubjects() {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Subject Name","Edit","Delete")); /* COLS */
	$sr->Columns(array("subject_id","subject_name","edit","del"));
	$sr->Query("SELECT subject_id,subject_name,'edit' AS edit,'delete' AS del
							FROM ".$GLOBALS['database_prefix']."scheduling_subject_master
							WHERE workspace_id = ".$GLOBALS['workspace_id']."
							AND teamspace_id ".$GLOBALS['teamspace_sql']."
							ORDER BY subject_name");

	for ($i=0;$i<$sr->CountRows();$i++) {
		$subject_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$sr->ModifyData($i,2,"<a href='index.php?module=scheduling&task=subjects&subtask=edit&subject_id=".$subject_id."' title='Edit'>Edit</a>");
		$sr->ModifyData($i,3,"<a href='index.php?module=scheduling&task=subjects&subtask=delete&subject_id=".$subject_id."' title='Delete'>Delete</a>");
	}
	$sr->RemoveColumn(0);

	$sr->WrapData();
	$sr->Footer();
	$sr->TableTitle("nuvola/32x32/apps/kuser.png","Browsing Master Subjects");
	return $sr->Draw();

}

?>