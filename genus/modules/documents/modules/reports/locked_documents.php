<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");
require_once($GLOBALS['dr']."include/functions/filesystem/size_int.php");
require_once($GLOBALS['dr']."modules/documents/classes/category_id.php");

function SubTask() {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Filename","Version","Category","Locked By","Date")); /* COLS */
	$sr->Columns(array("document_id","filename","version_number","category_id","full_name","date_locked"));
	$sr->Query("SELECT df.document_id, df.filename, df.version_number, df.category_id, um.full_name, df.date_locked
							FROM ".$GLOBALS['database_prefix']."v_document_files df, ".$GLOBALS['database_prefix']."core_user_master um
							WHERE df.workspace_id = ".$GLOBALS['workspace_id']."
							AND df.teamspace_id ".$GLOBALS['teamspace_sql']."
							AND df.locked = 'y'
							AND df.latest_version = 'y'
							AND df.user_id_locked = um.user_id
							ORDER BY df.filename
							");

	for ($i=0;$i<$sr->CountRows();$i++) {
		$document_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$filename=$sr->GetRowVal($i,1); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$sr->ModifyData($i,1,"<a href='index.php?module=documents&task=document_details&document_id=".$document_id."'>".$filename."</a>");

		$category_id=$sr->GetRowVal($i,3); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		/* THIS DISPLAYS THE HEIRARCHY OF THE CATEGORIES */
		$ci=new CategoryID;
		//$ci->SetParameters($category_id);
		$var=$ci->CategoryHeirarchy($category_id);
		$sr->ModifyData($i,3,$var);
	}
	$sr->RemoveColumn(0);

	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Locked Files");
	return $sr->Draw();
}
?>