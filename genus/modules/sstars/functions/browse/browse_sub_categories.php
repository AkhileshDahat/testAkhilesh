<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function BrowseSubCategories() {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Sub Category Name","Category","Edit","Delete")); /* COLS */
	$sr->Columns(array("sub_category_id","sub_category_name","category_name","edit","del"));
	$sr->Query("SELECT scm.sub_category_id,scm.sub_category_name,cm.category_name,'edit' AS edit,'delete' AS del
							FROM ".$GLOBALS['database_prefix']."sstars_sub_category_master scm, ".$GLOBALS['database_prefix']."sstars_category_master cm
							WHERE scm.workspace_id = ".$GLOBALS['workspace_id']."
							AND scm.teamspace_id ".$GLOBALS['teamspace_sql']."
							AND scm.category_id = cm.category_id
							ORDER BY category_name");

	for ($i=0;$i<$sr->CountRows();$i++) {
		$sub_category_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$sr->ModifyData($i,3,"<a href='index.php?module=sstars&task=sub_categories&subtask=edit&sub_category_id=".$sub_category_id."' title='Edit'>Edit</a>");
		$sr->ModifyData($i,4,"<a href='index.php?module=sstars&task=sub_categories&subtask=delete&sub_category_id=".$sub_category_id."' title='Delete'>Delete</a>");
	}
	$sr->RemoveColumn(0);

	$sr->WrapData();
	$sr->Footer();
	$sr->TableTitle("nuvola/32x32/apps/kuser.png","Browsing sub-categories");
	return $sr->Draw();

}

?>