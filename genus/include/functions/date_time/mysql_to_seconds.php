<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function MySQLDateToSeconds($dt) {
	$tempdate = split('[- :]', $dt);
	$year = $tempdate[0];
	$month = $tempdate[1];
	$daynum = $tempdate[2];

	if (STRLEN($dt) < 19) {
		$hour="00";
		$minute="00";
		$second="00";
	}
	else {
		$hour = $tempdate[3];
		$minute = $tempdate[4];
		$second = $tempdate[5];
	}

	return mktime($hour,$minute,$second,$month,$daynum,$year);
}
?>