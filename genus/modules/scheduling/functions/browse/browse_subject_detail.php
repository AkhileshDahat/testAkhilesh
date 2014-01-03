<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function BrowseSubjectDetail() {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Subject Name","Lecturer","Duration","Description","View","Delete")); /* COLS */
	$sr->Columns(array("subject_detail_id","subject_name","full_name","duration_hours","description","view","del"));
	$sr->Query("SELECT ssd.subject_detail_id, ssm.subject_name, um.full_name, ssd.duration_hours, ssd.description,'view' AS view,'delete' AS del
							FROM ".$GLOBALS['database_prefix']."core_user_master um, ".$GLOBALS['database_prefix']."scheduling_subject_master ssm, ".$GLOBALS['database_prefix']."scheduling_subject_detail ssd
							WHERE ssm.workspace_id = ".$GLOBALS['workspace_id']."
							AND ssm.teamspace_id ".$GLOBALS['teamspace_sql']."
							AND ssd.subject_id = ssm.subject_id
							AND ssd.user_id = um.user_id
							ORDER BY ssm.subject_name");

	for ($i=0;$i<$sr->CountRows();$i++) {
		$subject_detail_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$sr->ModifyData($i,5,"<a href='index.php?module=scheduling&task=subject_detail_browse&subject_detail_id=".$subject_detail_id."' title='View'>View</a>");
		$sr->ModifyData($i,6,"<a href='index.php?module=scheduling&task=subject_detail&subtask=delete&subject_detail_id=".$subject_detail_id."' title='Delete'>Delete</a>");
	}
	$sr->RemoveColumn(0);

	$sr->WrapData();
	$sr->Footer();
	$sr->TableTitle("nuvola/32x32/apps/kuser.png","Browsing Subject Detail");
	return $sr->Draw();

}

?>