<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."include/functions/forms/auto_redirect.php");
require_once($GLOBALS['dr']."include/functions/date_time/day_of_week.php");
require_once($GLOBALS['dr']."include/functions/date_time/date_to_seconds.php");
require_once $GLOBALS['dr']."classes/form/create_form.php";

require_once($GLOBALS['dr']."modules/facilities/classes/facility_master.php");
require_once($GLOBALS['dr']."modules/facilities/classes/booking_id.php");
require_once($GLOBALS['dr']."modules/facilities/functions/exists/facility_available.php");

function FacilitiesAvailability($facility_id="",$date="") {
	$ui=$GLOBALS['ui'];
	$wb=$GLOBALS['wb'];

	/* INCLUDE THE JCALENDAR IF NOT IN THE MOBILE VERSION */
	if (!defined( '_VALID_MVH_MOBILE_' )) {	$GLOBALS['head']->IncludeFile("jscalendar"); }

	/* FILTER FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=facilities&task=availability","post","facilities_availability");
	if (!defined( '_VALID_MVH_MOBILE_' )) {
		$form->Header("crystalclear/48x48/apps/access.png","Query Availability");
	}
	$form->ShowDropDown("Facility","facility_id","facility_name","facility_master","facility_id",$facility_id,"","WHERE workspace_id=".$GLOBALS['ui']->WorkspaceID()." AND teamspace_id ".$GLOBALS['teamspace_sql']."","");
	$form->Date("Date","date",$date);
	$form->Submit("Filter Now","FormSubmit");
	$form->CloseForm();
	$c=$form->DrawForm();

	/* THIS IS THE AVAILABILITY CHART */
	if (IS_NUMERIC($facility_id)) {
		$fm=new FacilityMaster;
		$fm->Info($facility_id);
		$var_booking_interval=$fm->GetInfo("booking_interval");
		$c.="<table border='0' class='plain'>\n";

		/* CHECK FOR BOOKING INTERVAL GREATER THAN 60 MINUTES */
		//if ($fm->GetInfo("booking_interval") == 120) { $i_plus=2; } elseif ($fm->GetInfo("booking_interval")==240) { $i_start=6; } elseif ($fm->GetInfo("booking_interval")==1440) { $i_start=1; } else { $i_start=24; $i_plus=2; }
		$i_plus=($fm->GetInfo("booking_interval")/60);
		if ($i_plus<1) { $i_plus=1; }
		/* START LOOPING */

		for ($i=0;$i<24;$i) {
			for ($j=0;$j<60;$j) {
				if ($i==0) { $i_show="00"; } else { if ($i<10) { $i_show="0".$i; } else { $i_show=$i; } }
				if ($j==0) { $j_show="00"; } else { $j_show=$j; }
				$friendly_val=$i_show.":".$j_show;
				$date_check=$date." ".$friendly_val.":00";
				$booking_id=FacilityAvailable($facility_id,$date_check);
				if (IS_NULL($booking_id)) {
					$booking_name="<img src='".$wb."images/spacer.gif' width='200' height='1'>";
					if (defined( '_VALID_MVH_MOBILE_' )) {
						$booking_name="<form method='post' action='index.php?module=facilities&task=availability&facility_id=".$facility_id."&date=".$date."&time_from=".$friendly_val."&time_to=".GetNextSlot($var_booking_interval,$date,$friendly_val)."&quick_booking=yes'><input type='submit' value='Book Slot Now'></form>";
					}
				}
				else {
					/* NEW BOOKING ID OBJECT */
					$bm=new BookingID($booking_id);
					$booking_name=$bm->GetInfo("full_name");
					$show="booked";
				}
				/* SOME DESIGN FEATURES NOT FOR MOBILE */
				if (!defined( '_VALID_MVH_MOBILE_' )) {
					$bgcolor="";
					$mouseover="class='alternatecell2' onMouseOver=\"this.className='alternatecell1'\" onMouseOut=\"this.className='alternatecell2'\"";
					$onclick="onClick=\"self.location.href='index.php?module=facilities&task=availability&facility_id=".$facility_id."&date=".$date."&time_from=".$friendly_val."&time_to=".GetNextSlot($var_booking_interval,$date,$friendly_val)."&quick_booking=yes'\"";
					$bookform="";
				}
				else {
					$bgcolor="bgcolor='#dedede'"; $mouseover="";
					$mouseover="";
					$onclick="";
					$onclick="";
				}
				$c.="<tr>\n";
					$c.="<td $mouseover>".$friendly_val."</td>\n";
					$c.="<td $bgcolor $mouseover $onclick>".$booking_name."</td>\n";
				$c.="</tr>\n";
				$j+=$var_booking_interval;
				//echo $fm->GetInfo("booking_interval");
				//$j+=15;
			}
			$i+=$i_plus;
		}

		$c.="</table>\n";

	}
	return $c;
}

function GetNextSlot($par_booking_interval,$par_date,$par_time_from) {
	$var_date_time=$par_date." ".$par_time_from.":00";
	$var_date_timestamp=(DateToSeconds($var_date_time)+($par_booking_interval*60));

	return date("H:i",$var_date_timestamp);
}
?>