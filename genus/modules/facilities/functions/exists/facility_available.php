<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function FacilityAvailable($facility_id,$date) {
	$db=$GLOBALS['db'];

 	$sql="SELECT booking_id
 	      FROM ".$GLOBALS['database_prefix']."facility_booking_master
 	      WHERE facility_id = ".$facility_id."
 	      AND date_from = '".$date."'
 	      ";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		while($row = $db->FetchArray($result)) {
			//echo $row['booking_id']."<br>";
			return $row['booking_id'];
		}
	}
	return Null;
}

?>