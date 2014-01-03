<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function FacilitiesBrowseBooking() {
	$ui=$GLOBALS['ui'];

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Logo","Name","Description")); /* COLS */
	$sr->Columns(array("facility_id","logo","facility_name","description"));
	$query="SELECT fm.facility_id,fm.logo,fm.facility_name,fm.description
					FROM ".$GLOBALS['database_prefix']."facility_master fm
					WHERE fm.workspace_id = ".$ui->WorkspaceID()."
					AND fm.teamspace_id ".$GLOBALS['teamspace_sql']."
					ORDER BY fm.facility_name";
	$sr->Query($query);

	for ($i=0;$i<$sr->CountRows();$i++) {
		$facility_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT LINES */
		$facility_name=$sr->GetRowVal($i,2); /* FASTER THAN CALLING EACH TIME IN THE NEXT LINES */
		$logo=$sr->GetRowVal($i,1); /* FASTER THAN CALLING EACH TIME IN THE NEXT LINES */
		$sr->ModifyData($i,2,"<a href='index.php?module=facilities&task=bookings&facility_id=".$facility_id."&jshow=step2' title='Click to select'>".$facility_name."</a>");
		$sr->ModifyData($i,1,"<img src='images/".$logo."'>");
	}

	$sr->RemoveColumn(0);
	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Browsing available facilities");
	return $sr->Draw();
}

?>