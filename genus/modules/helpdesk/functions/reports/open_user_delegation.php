<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function OpenUserDelegation() {

	$c="";

	$sr=new ShowResults;
	$sr->SetParameters(True);

	$sr->DrawFriendlyColHead(array("User","Total Tickets")); /* COLS */
	$sr->Columns(array("full_name","total"));

	$sql = "SELECT um.full_name, count(ht.ticket_id) as total
							FROM ".$GLOBALS['database_prefix']."helpdesk_tickets ht, ".$GLOBALS['database_prefix']."helpdesk_status_master hsm,
							".$GLOBALS['database_prefix']."helpdesk_ticket_delegation htd,
							".$GLOBALS['database_prefix']."core_user_master um
							WHERE ht.workspace_id = ".$GLOBALS['workspace_id']."
							AND ht.teamspace_id ".$GLOBALS['teamspace_sql']."
							AND ht.status_id = hsm.status_id
							AND (hsm.is_new = 'y' OR hsm.is_in_progress = 'y')
							AND ht.ticket_id = htd.ticket_id
							AND htd.user_id = um.user_id
							GROUP BY htd.user_id
							ORDER BY total DESC
							";
	//echo $sql;
	$sr->Query($sql);

	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Open ticket delegated");
	//$sr->Footer();
	$c.=$sr->Draw();

	return $c;
}

?>