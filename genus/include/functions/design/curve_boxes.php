<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function CurveBox($content) {
	$wb=$GLOBALS['wb'];
	$s="<table class='plain' width='90%' bgcolor='#F7F8FB' cellpadding='0' cellspacing='0' align='center' valign='top'>\n";
		$s.="<tr>\n";
			$s.="<td width='20'><img src='".$wb."images/curves/teamspace_top_left.gif'></td>\n";
			$s.="<td background='".$wb."images/curves/teamspace_top.gif'></td>\n";
			$s.="<td width='20'><img src='".$wb."images/curves/teamspace_top_right.gif'></td>\n";
		$s.="</tr>\n";
		$s.="<tr>\n";
			$s.="<td background='".$wb."images/curves/teamspace_left.gif'></td>\n";
			$s.="<td>\n";
			$s.=$content;
			$s.="</td>\n";
			$s.="<td background='".$wb."images/curves/teamspace_right.gif'></td>\n";
		$s.="</tr>\n";
		$s.="<tr>\n";
			$s.="<td width='20'><img src='".$wb."images/curves/teamspace_bottom_left.gif'></td>\n";
			$s.="<td background='".$wb."images/curves/teamspace_bottom.gif'></td>\n";
			$s.="<td width='20'><img src='".$wb."images/curves/teamspace_bottom_right.gif'></td>\n";
		$s.="</tr>\n";
	$s.="</table>\n";
	return $s;
}
?>