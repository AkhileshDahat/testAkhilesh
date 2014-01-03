<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

//require_once $GLOBALS['dr']."include/functions/db/get_col_value.php";

function HomeTaskInfo() {

	if (!defined('_VALID_MVH_MOBILE_')) {

		$s="<table class='plain' width='100%' height='400' cellpadding='0' cellspacing='0'>\n";
			$s.="<tr>\n";
				$s.="<td colspan='3' height='2' background='images/home/bg_faded_line.gif'><img src='images/spacer.gif'></td>\n";
			$s.="</tr>\n";
			$s.="<tr>\n";
				$s.="<td width='200' align='center' background='images/home/bg_faded_line_left.gif' class='homehead'><img src='modules/".$_GET['module']."/images/default/icon_module.png'><br>".InitCap($_GET['module'])."</td>";
				$s.="<td width='2' bgcolor='#336699'></td>";
				//$s.="<td width='500' align='center' valign='top' background='images/home/bg_faded_line_right.gif' class='homebody'><br><br></h3>Welcome to the module</h3></td>";
				$s.="<td width='500' align='center' valign='top' background='images/home/bg_faded_line_right.gif' class='homebody'>".ContentPanel()."</td>";
			$s.="</tr>\n";
			$s.="<tr>\n";
				$s.="<td colspan='3' height='2' background='images/home/bg_faded_line.gif'><img src='images/spacer.gif'></td>\n";
			$s.="</tr>\n";
		$s.="</table>\n";
	}
	else {
		return ShowMoreInfo();
	}
	return $s;
}

function ContentPanel() {

	$s="<table width='90%' height='100%' cellpadding='0' cellspacing='0'>\n";
		if (!ISSET($_GET['more_info'])) {
			$s.="<tr>\n";
				$s.="<td height='350'><h1 style='color:white'>Welcome</h1></td>";
			$s.="</tr>\n";
			$s.="<tr>\n";
				$s.="<td height='40' align='right'><a href='index.php?module=".EscapeData($_GET['module'])."&task=home&more_info=yes'><img src='images/home/learn_more.png' border='0'></a></td>";
			$s.="</tr>\n";
		}
		else {
			$s.="<tr>\n";
				$s.="<td><h1 style='color:white'>Welcome</h1></td>";
			$s.="</tr>\n";
			$s.="<tr>\n";
				$s.="<td><font style='color:white'>".ShowMoreInfo()."</font></td>";
			$s.="</tr>\n";
		}
	$s.="</table>\n";
	return $s;
}

function ShowMoreInfo() {
	return GetColumnValue("intro_description","core_help_more_info","module",EscapeData($_GET['module']),"");
}
?>