<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

//require_once($GLOBALS['dr']."classes/form/show_results.php");

//require_once($GLOBALS['dr']."include/functions/misc/yes_no_image.php");
//require_once($GLOBALS['dr']."include/functions/date_time/timestamptz_to_friendly.php");
//require_once($GLOBALS['dr']."include/functions/filesystem/size_int.php");

require_once($GLOBALS['dr']."modules/survey/classes/survey_id.php");

function SurveyIDSummary($survey_id) {

	//GLOBAL $survey_id;
	GLOBAL $si;
	$si=new SurveyID;
	$result=$si->SetParameters($survey_id);
	if (!$result) { return $si->ShowErrors(); }

	$c="<table border='0' cellpadding='0' width='100%' class='plain'>\n";
		$c.="<tr class='modulehead'>\n";
			$c.="<td colspan='2'>Survey Details</td>\n";
		$c.="</tr>\n";
		$c.=ShowRow("Description","description");
		$c.=ShowRow("Date Open","date_open");
		$c.=ShowRow("Date Closed","date_closed");
		$c.=ShowRow("Password","public_password");

	$c.="</table>\n";

	return $c;
}

function ShowRow($desc,$val) {
	$si=$GLOBALS['si'];
	$c="<tr>\n";
		$c.="<td class='bold'>".$desc."</td>\n";
		$c.="<td>".$si->GetInfo($val)."</td>\n";
	$c.="</tr>\n";
	return $c;
}

function ShowYesNoRow($desc,$val) {
	$si=$GLOBALS['si'];
	$c="<tr>\n";
		$c.="<td class='bold'>".$desc."</td>\n";
		$c.="<td>".YesNoImage($si->GetInfo($val))."</td>\n";
	$c.="</tr>\n";
	return $c;
}

function ShowBreak() {
	$c="<tr>\n";
		$c.="<td colspan='2'><hr></td>\n";
	$c.="</tr>\n";
	return $c;
}
?>