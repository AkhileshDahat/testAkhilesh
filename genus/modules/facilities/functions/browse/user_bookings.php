<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");
require_once($GLOBALS['dr']."include/functions/date_time/friendly_date_from_to.php");

function UserBookings($user_id,$workspace_id,$teamspace_id) {
	$ui=$GLOBALS['ui'];

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Facility","Date")); /* COLS */
	$sr->Columns(array("booking_id","facility_name","date_from","date_to"));
	$query="SELECT fbm.booking_id,fm.facility_name,fbm.date_from,fbm.date_to
					FROM ".$GLOBALS['database_prefix']."facility_booking_master fbm, ".$GLOBALS['database_prefix']."facility_master fm,
					".$GLOBALS['database_prefix']."facility_status_master fsm
					WHERE fbm.user_id = ".$_SESSION['user_id']."
					AND fbm.workspace_id = ".$ui->WorkspaceID()."
					AND fbm.teamspace_id ".$GLOBALS['teamspace_sql']."
					AND fbm.date_from >= now()
					AND fbm.facility_id = fm.facility_id
					AND fbm.status_id = fsm.status_id
					ORDER BY fbm.date_from";
	$sr->Query($query);

	for ($i=0;$i<$sr->CountRows();$i++) {
		$date_from=$sr->GetRowVal($i,2); /* FASTER THAN CALLING EACH TIME IN THE NEXT LINES */
		$date_to=$sr->GetRowVal($i,3); /* FASTER THAN CALLING EACH TIME IN THE NEXT LINES */
		$sr->ModifyData($i,2,FriendlyDateFromTo($date_from,$date_to));
	}

	$sr->RemoveColumn(0);
	$sr->RemoveColumn(3);
	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Browsing my bookings");
	return $sr->Draw("250");
}

?>