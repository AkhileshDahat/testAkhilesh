<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function BrowseCategories() {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Category Name","Edit","Delete")); /* COLS */
	$sr->Columns(array("category_id","category_name","edit","del"));
	$sr->Query("SELECT category_id,category_name,'edit' AS edit,'delete' AS del
							FROM ".$GLOBALS['database_prefix']."project_category_master
							WHERE workspace_id = ".$GLOBALS['workspace_id']."
							AND teamspace_id ".$GLOBALS['teamspace_sql']."
							ORDER BY category_name");

	for ($i=0;$i<$sr->CountRows();$i++) {
		$category_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$sr->ModifyData($i,2,"<a href='index.php?module=projects&task=categories&subtask=edit&category_id=".$category_id."' title='Edit'>Edit</a>");
		$sr->ModifyData($i,3,"<a href='index.php?module=projects&task=categories&subtask=delete&category_id=".$category_id."' title='Delete'>Delete</a>");
	}
	$sr->RemoveColumn(0);

	$sr->WrapData();
	$sr->Footer();
	$sr->TableTitle("nuvola/32x32/apps/kuser.png","Browsing categories");
	return $sr->Draw();

}

?>