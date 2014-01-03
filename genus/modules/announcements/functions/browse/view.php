<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function View() {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Title","Message","Date","User")); /* COLS */
	$sr->Columns(array("announcement_id","title","message","date_added","full_name"));
	$sr->Query("SELECT am.announcement_id,am.title,LEFT(am.message,15) as message,am.date_added,um.full_name
						FROM ".$GLOBALS['database_prefix']."announcements_master am,".$GLOBALS['database_prefix']."core_user_master um
						WHERE am.workspace_id = ".$GLOBALS['workspace_id']."
						AND am.teamspace_id ".$GLOBALS['teamspace_sql']."		
						AND am.user_id = um.user_id
						ORDER BY am.announcement_id DESC
						");

	//for ($i=0;$i<$sr->CountRows();$i++) {
		//$department_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		//$sr->ModifyData($i,2,"<a href='index.php?module=hrms&task=departments&subtask=edit&department_id=".$department_id."' title='Edit'>Edit</a>");
		//$sr->ModifyData($i,3,"<a href='index.php?module=hrms&task=departments&subtask=delete&department_id=".$department_id."' title='Delete'>Delete</a>");
	//}
	$sr->RemoveColumn(0);

	$sr->WrapData();
	$sr->Footer();
	$sr->TableTitle("nuvola/32x32/apps/kuser.png","Announcements");
	return $sr->Draw();

}

?>