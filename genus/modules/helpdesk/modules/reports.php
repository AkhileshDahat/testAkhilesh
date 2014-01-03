<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function LoadTask() {
	$c="";

	if (ISSET($_GET['report']) && file_exists($GLOBALS['dr']."/modules/helpdesk/functions/reports/".$_GET['report'].".php")) {
		require_once $GLOBALS['dr']."/modules/helpdesk/functions/reports/".$_GET['report'].".php";
		$v_temp_function_call=STR_REPLACE("_","",$_GET['report']);
		$c.=$v_temp_function_call();
	}
	else {
		$c.="<table class='plain_border'>\n";
			$c.="<tr>\n";
				$c.="<td class='colhead' colspan='2'>Reports</td>\n";
			$c.="</tr>\n";
			$c.="<tr>\n";
				$c.="<td><a href='index.php?module=helpdesk&task=reports&report=status'>Tickets by status</a></td>\n";
				$c.="<td width='66%' class='footer'>Shows all tickets grouped by status</td>\n";
			$c.="</tr>\n";
			$c.="<tr>\n";
				$c.="<td><a href='index.php?module=helpdesk&task=reports&report=open_user_delegation'>Open tickets per user</a></td>\n";
				$c.="<td width='66%' class='footer'>View the number of open tickets delegated to each user</td>\n";
			$c.="</tr>\n";
			$c.="<tr>\n";
				$c.="<td><a href='index.php?module=helpdesk&task=reports&report=tickets_monthly_totals'>Monthly tickets</a></td>\n";
				$c.="<td width='66%' class='footer'>View the number of tickets submitted on a monthly basis</td>\n";
			$c.="</tr>\n";
			$c.="<tr>\n";
				$c.="<td><a href='index.php?module=helpdesk&task=reports&report=category_trend'>Category Trend</a></td>\n";
				$c.="<td width='66%' class='footer'>View the ticket totals of each category</td>\n";
			$c.="</tr>\n";

		$c.="</table>\n";
	}
	return $c;
}
?>