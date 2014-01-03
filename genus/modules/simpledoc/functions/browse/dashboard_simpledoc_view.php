<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function DashboardSimpledocView() {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","File")); /* COLS */
	$sr->Columns(array("document_id","filename"));
	$sr->Query("SELECT sm.document_id,sm.filename
						FROM ".$GLOBALS['database_prefix']."simpledoc_files sm,".$GLOBALS['database_prefix']."core_user_master um
						WHERE sm.workspace_id = ".$GLOBALS['workspace_id']."
						AND sm.teamspace_id ".$GLOBALS['teamspace_sql']."
						AND sm.user_id = um.user_id
						ORDER BY sm.document_id DESC	
						");

	for ($i=0;$i<$sr->CountRows();$i++) {
		$document_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$filename=$sr->GetRowVal($i,1); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$sr->ModifyData($i,1,"<a href='modules/simpledoc/bin/download_document.php?document_id=".$document_id."' title='View'>".$filename."</a>");				
	}
	$sr->RemoveColumn(0);

	$sr->WrapData();
	//$sr->Footer();
	$sr->TableTitle("","<a href='index.php?module=simpledoc&task=home'>Latest Files</a>");
	return $sr->Draw();

}

?>