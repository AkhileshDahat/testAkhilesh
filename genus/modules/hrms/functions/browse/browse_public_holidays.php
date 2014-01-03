<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function BrowsePublicHolidays() {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("Date","Delete")); /* COLS */
	$sr->Columns(array("date_pub_hol","del"));
	$sr->Query("SELECT date_pub_hol,'delete' AS del
							FROM ".$GLOBALS['database_prefix']."hrms_public_holiday_master
							WHERE workspace_id = ".$GLOBALS['workspace_id']."
							AND teamspace_id ".$GLOBALS['teamspace_sql']."
							ORDER BY date_pub_hol");

	for ($i=0;$i<$sr->CountRows();$i++) {
		$date_pub_hol=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$sr->ModifyData($i,1,"<a href='index.php?module=hrms&task=public_holidays&subtask=delete&date_pub_hol=".$date_pub_hol."' title='Delete'>Delete</a>");
	}
	//$sr->RemoveColumn(0);

	$sr->WrapData();
	$sr->Footer();
	$sr->TableTitle("nuvola/32x32/apps/kuser.png","Browsing Public Holidays");
	return $sr->Draw();

}

?>