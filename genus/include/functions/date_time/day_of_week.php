<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* EXPECT TIME IN THE FORMAT 23:59:39 */

function DayOfWeek($d,$short=False) {

	if ($d==1) { $day="Monday"; }
	elseif ($d==2) { $day="Tuesday"; }
	elseif ($d==3) { $day="Wednesday"; }
	elseif ($d==4) { $day="Thursday"; }
	elseif ($d==5) { $day="Friday"; }
	elseif ($d==6) { $day="Saturday"; }
	elseif ($d==7) { $day="Sunday"; }

	if ($short) { return SUBSTR($day,0,3); } else { return $day; }
}
?>