<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function BrowseAccounts() {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Account Name","Account Manager","Delete")); /* COLS */
	$sr->Columns(array("account_id","account_name","full_name","delete"));
	$sr->Query("SELECT account_id, account_name, full_name, 'Delete' As delete
							FROM ".$GLOBALS['database_prefix']."v_crm_accounts
							WHERE workspace_id = ".$GLOBALS['workspace_id']."
							AND teamspace_id ".$GLOBALS['teamspace_sql']."
							");
	for ($i=0;$i<$sr->CountRows();$i++) {
		$account_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		//$sr->ModifyData($i,3,"<a href='index.php?module=documents&task=pending_approval&account_id=".$account_id."&approve=y' title='Click to approve'>Approve</a>");
		//$sr->ModifyData($i,4,"<a href='index.php?module=documents&task=pending_approval&account_id=".$account_id."&approve=n' title='Click to reject'>Reject</a>");
	}
	$sr->RemoveColumn(0);

	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Browsing Accounts");
	return $sr->Draw();


}

?>