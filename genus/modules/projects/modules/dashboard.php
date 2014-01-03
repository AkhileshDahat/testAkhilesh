<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/procedure/return_result_procedure.php";

//echo CurveBox(Home());

function LoadTask() {

	$wb=$GLOBALS['wb'];
	$c="<table cellspacing='10' cellpadding='0' width='550' class='plain'>\n";
		$c.="<tr>\n";
			$c.="<td colspan='5'>\n";
			$c.="<table>\n";
				$c.="<tr>\n";
					$c.="<td><img src='".$wb."modules/projects/images/home/logo48x48.png'></td>\n";
					$c.="<td class='colhead'>Projects<br><font class='font11'>Project Summary Page</font><td>\n";
				$c.="</tr>\n";
			$c.="</table>\n";
			$c.="</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td class='colhead' valign='top' width='33%'>About<br>".ProjectAbout()."</td>\n";
			$c.="<td width='1' bgcolor='#8892B4'><img src='".$wb."images/spacer.gif' width='1' height='1'></td>\n";
			$c.="<td class='colhead' valign='top' width='33%'>Browse".ProjectBrowse()."</td>\n";
			$c.="<td width='1' bgcolor='#8892B4'><img src='".$wb."images/spacer.gif' width='1' height='1'></td>\n";
			$c.="<td class='colhead' valign='top' width='33%'>About</td>\n";
		$c.="</tr>\n";
	$c.="</table>\n";

	return $c;
}

//echo ReturnResultProcedure("projects_count_active(@a)","count(*)");

function ProjectAbout() {
	$c="<table class='plain'>\n";
		$c.="<tr>\n";
			$c.="<td>Welcome to the projects module. Here you can find information about the latest projects.</td>\n";
		$c.="</tr>\n";
	$c.="</table>\n";

	return $c;
}

function ProjectBrowse() {
	$c="<table cellpadding='5' class='plain'>\n";
		$c.="<tr>\n";
			$c.="<td><img src='images/nuvola/48x48/misc/calendar_year.png'></td>\n";
			$c.="<td valign='center'>Yearly View</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td><img src='images/nuvola/48x48/misc/calendar_month.png'></td>\n";
			$c.="<td valign='center'>Monthly View</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td><img src='images/nuvola/48x48/misc/calendar_day.png'></td>\n";
			$c.="<td valign='center'>Daily View</td>\n";
		$c.="</tr>\n";
	$c.="</table>\n";

	return $c;
}
?>