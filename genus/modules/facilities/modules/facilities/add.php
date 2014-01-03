<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/facilities/functions/forms/add_facility.php";

function Add() {

	if (ISSET($_GET['subtask']) && $_GET['subtask']=="edit" && ISSET($_GET['facility_id']) && IS_NUMERIC($_GET['facility_id'])) {
		require_once $GLOBALS['dr']."modules/facilities/classes/facility_master.php";
		$fm=new FacilityMaster;
		$facility_id=$_GET['facility_id'];
		$fm->Info($facility_id);
		$facility_name=$fm->GetInfo("facility_name");
		$description=$fm->GetInfo("description");
		$logo=$fm->GetInfo("logo");
		$max_user_bookings_week=$fm->GetInfo("max_user_bookings_week");
	}
	else {
		$facility_id="";
		$facility_name="";
		$description="";
		$logo="";
		$max_user_bookings_week="";
	}

	return CurveBox(AddFacility($facility_id,$facility_name,$description,$logo,$max_user_bookings_week));
}
?>