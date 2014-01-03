<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function Menu($user_id) {
	$ui=$GLOBALS['ui'];
	$c="<table class='plain' width='200'>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2' class='bold'>Facilities Menu</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td width='16'><img src='images/nuovext/16x16/actions/edit_add.png'></td>\n";
			$c.="<td><a href='index.php?module=facilities&task=home'>Home</a></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td width='16'><img src='images/nuovext/16x16/actions/edit_add.png'></td>\n";
			$c.="<td><a href='index.php?module=facilities&task=my_bookings'>My Bookings</a></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td width='16'><img src='images/nuovext/16x16/actions/edit_add.png'></td>\n";
			$c.="<td><a href='index.php?module=facilities&task=availability'>Browse Availability</a></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td width='16'><img src='images/nuovext/16x16/actions/edit_add.png'></td>\n";
			$c.="<td><a href='index.php?module=facilities&task=bookings'>Bookings</a></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td width='16'><img src='images/nuovext/16x16/actions/edit_add.png'></td>\n";
			$c.="<td><a href='index.php?module=facilities&task=facilities'>Browse / Add Facility</a></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td width='16'><img src='images/nuovext/16x16/actions/edit_add.png'></td>\n";
			$c.="<td><a href='index.php?module=facilities&task=acl'>Access Control</a></td>\n";
		$c.="</tr>\n";
	$c.="</table>\n";
	return $c;
}