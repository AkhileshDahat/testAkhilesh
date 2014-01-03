<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/helpdesk/functions/reports/open_user_delegation.php";

function LoadTask() {
	$c="";


	$c.="<table class='plain'>\n";
		$c.="<tr>\n";
			$c.="<td class='colhead' colspan='2'>Dashboard</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top'><img src='modules/helpdesk/bin/reports/dash_total_tickets_last_3_months.php'></td>\n";
			$c.="<td valign='top'>".OpenUserDelegation()."</td>\n";
		$c.="</tr>\n";
	$c.="</table>\n";

	return $c;


}
?>