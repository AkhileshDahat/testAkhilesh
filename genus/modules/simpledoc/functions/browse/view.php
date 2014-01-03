<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function View() {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","File","User","Date","Delete")); /* COLS */
	$sr->Columns(array("document_id","filename","full_name","date_added","del"));
	$sr->Query("SELECT sm.document_id,sm.filename,um.full_name,sm.date_added,'del' as del
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
		$sr->ModifyData($i,4,"<a href='index.php?module=simpledoc&task=view&subtask=delete&document_id=".$document_id."' title='Delete'>Delete</a>");		
	}
	$sr->RemoveColumn(0);

	$sr->WrapData();
	$sr->Footer();
	$sr->TableTitle("nuvola/32x32/apps/kuser.png","Files");
	return $sr->Draw();

}

?>