<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/facilities/classes/facility_master.php";
require_once $GLOBALS['dr']."modules/facilities/functions/forms/add_times.php";

function Times() {
	$c="";
	$fm=new FacilityMaster; /* INSTANTIATE THE OBJECT SINCE WE NEED IT REGARDLESS OF EDITING */

	if (ISSET($_GET['subtask']) && $_GET['subtask']=="times" && ISSET($_GET['facility_id']) && IS_NUMERIC($_GET['facility_id'])) {

		/* CHECK FOR NULL TIMES */
		if (ISSET($_POST['mon_na'])) { $mon_from=Null; } else { $mon_from=$_POST['mon_from']; }
		if (ISSET($_POST['mon_na'])) { $mon_to=Null; } else { $mon_to=$_POST['mon_to']; }
		if (ISSET($_POST['tue_na'])) { $tue_from=Null; } else { $tue_from=$_POST['tue_from']; }
		if (ISSET($_POST['tue_na'])) { $tue_to=Null; } else { $tue_to=$_POST['tue_to']; }
		if (ISSET($_POST['wed_na'])) { $wed_from=Null; } else { $wed_from=$_POST['wed_from']; }
		if (ISSET($_POST['wed_na'])) { $wed_to=Null; } else { $wed_to=$_POST['wed_to']; }
		if (ISSET($_POST['thur_na'])) { $thur_from=Null; } else { $thur_from=$_POST['thur_from']; }
		if (ISSET($_POST['thur_na'])) { $thur_to=Null; } else { $thur_to=$_POST['thur_to']; }
		if (ISSET($_POST['fri_na'])) { $fri_from=Null; } else { $fri_from=$_POST['fri_from']; }
		if (ISSET($_POST['fri_na'])) { $fri_to=Null; } else { $fri_to=$_POST['fri_to']; }
		if (ISSET($_POST['sat_na'])) { $sat_from=Null; } else { $sat_from=$_POST['sat_from']; }
		if (ISSET($_POST['sat_na'])) { $sat_to=Null; } else { $sat_to=$_POST['sat_to']; }
		if (ISSET($_POST['sun_na'])) { $sun_from=Null; } else { $sun_from=$_POST['sun_from']; }
		if (ISSET($_POST['sun_na'])) { $sun_to=Null; } else { $sun_to=$_POST['sun_to']; }


		$result=$fm->Times($_GET['facility_id'],$mon_from,$mon_to,$tue_from,$tue_to,$wed_from,$wed_to,$thur_from,$thur_to,$fri_from,$fri_to,$sat_from,$sat_to,$sun_from,$sun_to);
		if ($result) {
			$c.=Alert(95);
		}
		else {
			$c.=Alert(94,$fm->ShowErrors());
		}
	}

	if ($_GET['task']=="times" && ISSET($_GET['facility_id']) && IS_NUMERIC($_GET['facility_id'])) {

		/* POPULATE THE FORM */
		$fm->InfoTimes($_GET['facility_id']);
		$facility_id=EscapeData($_GET['facility_id']);
		$mon_from=$fm->GetInfo("mon_from");
		$mon_to=$fm->GetInfo("mon_to");
		$tue_from=$fm->GetInfo("tue_from");
		$tue_to=$fm->GetInfo("tue_to");
		$wed_from=$fm->GetInfo("wed_from");
		$wed_to=$fm->GetInfo("wed_to");
		$thur_from=$fm->GetInfo("thur_from");
		$thur_to=$fm->GetInfo("thur_to");
		$fri_from=$fm->GetInfo("fri_from");
		$fri_to=$fm->GetInfo("fri_to");
		$sat_from=$fm->GetInfo("sat_from");
		$sat_to=$fm->GetInfo("sat_to");
		$sun_from=$fm->GetInfo("sun_from");
		$sun_to=$fm->GetInfo("sun_to");

		$c.=CurveBox(AddTimes($facility_id,$mon_from,$mon_to,$tue_from,$tue_to,$wed_from,$wed_to,$thur_from,$thur_to,$fri_from,$fri_to,$sat_from,$sat_to,$sun_from,$sun_to));
	}
	else {
		$c.="You must choose a valid facility before editing times";
	}

	return $c;
}
?>