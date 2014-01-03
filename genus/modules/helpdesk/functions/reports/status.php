<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function Status() {

	$c="";

	$sr=new ShowResults;
	$sr->SetParameters(True);

	$sr->DrawFriendlyColHead(array("Status","Total")); /* COLS */
	$sr->Columns(array("status_name","total"));
	$sr->Query("SELECT hsm.status_name, count(*) as total
							FROM ".$GLOBALS['database_prefix']."helpdesk_tickets ht, ".$GLOBALS['database_prefix']."helpdesk_status_master hsm
							WHERE ht.workspace_id = ".$GLOBALS['workspace_id']."
							AND ht.teamspace_id ".$GLOBALS['teamspace_sql']."
							AND ht.status_id = hsm.status_id
							GROUP BY ht.status_id
							");

	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","All tickets by status");
	//$sr->Footer();
	$c.=$sr->Draw();

	return $c;
}

?>