<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function DashboardAnnouncementsView() {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Date","Title")); /* COLS */
	$sr->Columns(array("announcement_id","date_submitted","title"));
	$sr->Query("SELECT am.announcement_id,date_format(date_added,'%e-%b') as date_submitted,am.title
						FROM ".$GLOBALS['database_prefix']."announcements_master am
						WHERE am.workspace_id = ".$GLOBALS['workspace_id']."
						AND am.teamspace_id ".$GLOBALS['teamspace_sql']."
						ORDER BY am.announcement_id DESC
						");

	for ($i=0;$i<$sr->CountRows();$i++) {
		$announcement_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$title=$sr->GetRowVal($i,2); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$sr->ModifyData($i,2,"<a href='index.php?module=announcements&task=details&announcement_id=".$announcement_id."' title='View'>".$title."</a>");
	}
	$sr->RemoveColumn(0);

	$sr->WrapData();
	//$sr->Footer();
	$sr->TableTitle("","<a href='index.php?module=announcements&task=home'>Announcements</a>");
	return $sr->Draw();

}

?>