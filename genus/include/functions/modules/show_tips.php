<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function ShowTips($module_id) {
	$wb=$GLOBALS['wb'];
	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","",)); /* COLS */
	$sr->Columns(array("description","logo"));
	$sr->Query("SELECT description,logo
							FROM ".$GLOBALS['database_prefix']."module_tips
							WHERE module_id = '".$module_id."'
							");

	for ($i=0;$i<$sr->CountRows();$i++) {
		$logo=$sr->GetRowVal($i,1); /* FASTER THAN CALLING EACH TIME IN THE NEXT LINES */
		$sr->ModifyData($i,1,"<img src='".$wb."images/".$logo."'>");
	}

	$sr->WrapData();
	//$sr->TableTitle("nuovext/22x22/actions/info.png","Tips");
	$sr->TableTitle("","Tips");
	return $sr->Draw();
}

?>