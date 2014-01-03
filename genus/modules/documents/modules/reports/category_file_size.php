<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");
require_once($GLOBALS['dr']."include/functions/filesystem/size_int.php");
require_once($GLOBALS['dr']."modules/documents/classes/category_id.php");

function SubTask() {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Category","Size")); /* COLS */
	$sr->Columns(array("category_id","tree","category_size"));
	$sr->Query("SELECT dc.category_id, 'tree' as tree, SUM(df.filesize) as category_size
							FROM ".$GLOBALS['database_prefix']."document_categories dc, ".$GLOBALS['database_prefix']."document_files df
							WHERE workspace_id = ".$GLOBALS['workspace_id']."
							AND teamspace_id ".$GLOBALS['teamspace_sql']."
							AND dc.category_id = df.category_id
							GROUP BY dc.category_id
							ORDER BY category_size DESC
							");

	for ($i=0;$i<$sr->CountRows();$i++) {
		$category_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$category_size=$sr->GetRowVal($i,2); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		/* THIS DISPLAYS THE HEIRARCHY OF THE CATEGORIES */
		$ci=new CategoryID;
		//$ci->SetParameters($category_id);
		$var=$ci->CategoryHeirarchy($category_id);
		$sr->ModifyData($i,1,$var);
		$sr->ModifyData($i,2,SizeFromInt($category_size));
	}
	$sr->RemoveColumn(0);

	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Category Sizes");
	return $sr->Draw();
}
?>