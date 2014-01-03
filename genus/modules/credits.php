<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function LoadModule() {

	$c.="<table class='plain'>\n";
		$c.="<tr>\n";
			$c.="<td><img src='images/buttons/rss.gif'></td>\n";
			$c.="<td>The use of RSS for reports</td>\n";
		$c.="</tr>\n";
	$c.="</table>\n";

	return $c;
}
?>