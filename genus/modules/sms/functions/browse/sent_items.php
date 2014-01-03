<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function SentItems() {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Message","Date","Sent WU","User")); /* COLS */
	$sr->Columns(array("sms_id","message","date_sent","sent_all_users_workspace","full_name"));
	$sr->Query("SELECT mm.sms_id,mm.message,mm.date_sent,mm.sent_all_users_workspace,um.full_name
							FROM ".$GLOBALS['database_prefix']."sms_message_master mm, ".$GLOBALS['database_prefix']."core_user_master um
							WHERE mm.workspace_id = ".$GLOBALS['workspace_id']."
							AND mm.teamspace_id ".$GLOBALS['teamspace_sql']."
							AND mm.user_id = um.user_id
							ORDER BY mm.sms_id DESC
						");

	//for ($i=0;$i<$sr->CountRows();$i++) {
		//$department_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		//$sr->ModifyData($i,2,"<a href='index.php?module=hrms&task=departments&subtask=edit&department_id=".$department_id."' title='Edit'>Edit</a>");
		//$sr->ModifyData($i,3,"<a href='index.php?module=hrms&task=departments&subtask=delete&department_id=".$department_id."' title='Delete'>Delete</a>");
	//}
	$sr->RemoveColumn(0);

	$sr->WrapData();
	$sr->Footer();
	$sr->TableTitle("nuvola/32x32/apps/kuser.png","Sent Messages");
	return $sr->Draw();

}

?>