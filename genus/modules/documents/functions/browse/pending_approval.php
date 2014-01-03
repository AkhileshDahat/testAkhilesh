<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function PendingApproval() {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","File","Version","Approve","Reject")); /* COLS */
	$sr->Columns(array("document_id","filename","version_number","approve","reject"));
	$sr->Query("SELECT v.document_id, v.filename, v.version_number, 'Approve' As approve, 'Reject' As reject
							FROM ".$GLOBALS['database_prefix']."document_file_approval dfa, ".$GLOBALS['database_prefix']."v_document_files v
							WHERE dfa.user_id = ".$_SESSION['user_id']."
							AND dfa.approved = 'n'
							AND dfa.document_id = v.document_id
							AND v.workspace_id = ".$GLOBALS['workspace_id']."
							AND v.teamspace_id ".$GLOBALS['teamspace_sql']."
							");

	for ($i=0;$i<$sr->CountRows();$i++) {
		$document_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$sr->ModifyData($i,3,"<a href='index.php?module=documents&task=pending_approval&document_id=".$document_id."&approve=y' title='Click to approve'>Approve</a>");
		$sr->ModifyData($i,4,"<a href='index.php?module=documents&task=pending_approval&document_id=".$document_id."&approve=n' title='Click to reject'>Reject</a>");
	}
	$sr->RemoveColumn(0);

	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Documents Pending Approval");
	return $sr->Draw();


}

?>