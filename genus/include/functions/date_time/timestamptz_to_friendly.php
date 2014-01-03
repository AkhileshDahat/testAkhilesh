<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/date_time/date_to_seconds.php";

function TimestampTZToFriendly($t) {
	//echo $t."<br>";
	$year  = substr($t, 0, 4);
	$month = substr($t, 5, 2);
	$day  = substr($t, 8, 2);
	$hour  = substr($t, 11, 2);
	$min  = substr($t, 14, 2);
	$sec  = substr($t, 17, 2);

	//echo $year."-".$month."-".$day." ".$hour.":".$min.":".$sec."<br><hr>";

	$date_seconds=DateToSeconds($t);
	$difference=time()-$date_seconds;
	//echo $difference."<br>";
	if ($difference < 60) { return "Less than 1 minute ago"; }
	elseif ($difference < 3600 ) { return $min." minutes ago"; }
	elseif ($difference < 86400 ) { return $hour." hour(s) and ".$min." mins ago"; }
	else { return $year."-".$month."-".$day; }
}

?>