<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function Menu($user_id) {
	$c="<table class='plain' width='150'>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2' class='bold'>Group Menu</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td width='16'><img src='images/nuvola/16x16/actions/view_text.png'></td>\n";
			$c.="<td><a href='index.php?module=groups&task=group_list'>Browse</a></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td width='16'><img src='images/nuvola/16x16/actions/viewmag.png'></td>\n";
			$c.="<td><a href='index.php?module=groups&task=add'>Add</a></td>\n";
		$c.="</tr>\n";
	$c.="</table>\n";
	return $c;
}