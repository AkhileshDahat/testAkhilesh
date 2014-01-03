<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");
require_once($GLOBALS['dr']."modules/documents/classes/category_id.php");

function MyBookmarks() {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Category","Path","Delete")); /* COLS */
	$sr->Columns(array("category_id","category_name","tree","del"));
	$sr->Query("SELECT dub.category_id, dc.category_name, 'tree' as tree, 'del' AS del
							FROM ".$GLOBALS['database_prefix']."document_user_bookmarks dub, ".$GLOBALS['database_prefix']."document_categories dc
							WHERE dub.user_id = ".$_SESSION['user_id']."
							AND	dub.category_id = dc.category_id
							AND workspace_id = ".$GLOBALS['workspace_id']."
							AND teamspace_id ".$GLOBALS['teamspace_sql']."
							");
	for ($i=0;$i<$sr->CountRows();$i++) {
		$category_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */

		/* THIS IS THE LINK TO THE ACTUAL CATEGORY */
		$sr->ModifyData($i,1,"<a href='index.php?module=documents&task=home&category_id=".$category_id."' title='Click to go'>View</a>");

		/* THIS DISPLAYS THE HEIRARCHY OF THE CATEGORIES */
		$ci=new CategoryID;
		//$ci->SetParameters($category_id);
		$var=$ci->CategoryHeirarchy($category_id);
		$sr->ModifyData($i,2,$var);

		/* THIS ADDS IN THE DELETE LINE */
		$sr->ModifyData($i,3,"<a href='index.php?module=documents&task=my_bookmarks&subtask=delete&category_id=".$category_id."' title='Delete'>Delete</a>");
	}
	$sr->RemoveColumn(0);

	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","My Bookmarks");
	return $sr->Draw();
}

?>