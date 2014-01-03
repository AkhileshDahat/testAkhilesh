<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* EXPECT TIME IN THE FORMAT 23:59:39 */

function ValidTime($t) {

	$temptime = split('[:]', $t);
	$hour = $temptime[0];
	$minute = $temptime[1];
	$second = @$temptime[2]; /* MAY NOT EXIST */

	if (!IS_NULL($hour) && (!IS_NUMERIC($hour) || ($hour < 0) || ($hour > 60))) { return False; }
	if (!IS_NULL($minute) && (!IS_NUMERIC($minute) || ($minute < 0) || ($minute > 60))) { return False; }
	if (!IS_NULL($second) && (!IS_NUMERIC($second) || ($second < 0) || ($second > 60))) { return False; }

	return True;
}
?>