<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* EXPECT DATE IN THE FORMAT 2005-12-31 */

function ValidDate($dt) {

	/* SPLIT THE VARIABLE */
	$tempdate = split('[- :]', $dt);
	$year = $tempdate[0];
	$month = $tempdate[1];
	$day = $tempdate[2];

	/* VALIDATE THE DATE */
	if (!checkdate($month,$day,$year)) { return False; }

	/* RETURN TRUE IF NO ERRORS FOUND */
	return True;
}
?>