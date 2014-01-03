<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function FacilitiesBrowse() {
	$ui=$GLOBALS['ui'];

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Logo","Name","Description","Setup","Delete")); /* COLS */
	$sr->Columns(array("facility_id","logo","facility_name","description","setup","del"));
	$query="SELECT fm.facility_id,fm.logo,fm.facility_name,fm.description,'setup' AS setup,'del' AS del
					FROM ".$GLOBALS['database_prefix']."facility_master fm
					WHERE workspace_id = ".$ui->WorkspaceID()."
					AND teamspace_id ".$GLOBALS['teamspace_sql']."
					ORDER BY fm.facility_name";
	$sr->Query($query);

	for ($i=0;$i<$sr->CountRows();$i++) {
		$facility_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT LINES */
		$facility_name=$sr->GetRowVal($i,2); /* FASTER THAN CALLING EACH TIME IN THE NEXT LINES */
		$logo=$sr->GetRowVal($i,1); /* FASTER THAN CALLING EACH TIME IN THE NEXT LINES */
		$sr->ModifyData($i,2,"<a href='index.php?module=facilities&task=facilities&subtask=edit&facility_id=".$facility_id."&jshow=add' title='Click to edit'>".$facility_name."</a>");
		$sr->ModifyData($i,1,"<img src='images/".$logo."'>");
		$sr->ModifyData($i,4,"<a href='index.php?module=facilities&task=facilities&facility_id=".$facility_id."&jshow=times' title='Click to setup'>Setup</a>");
		$sr->ModifyData($i,5,"<a href='index.php?module=facilities&task=facilities&subtask=delete&facility_id=".$facility_id."' title='Click to delete'>Delete</a>");
	}
	$sr->RemoveColumn(0);
	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Browsing available facilities");
	return $sr->Draw();
}

?>