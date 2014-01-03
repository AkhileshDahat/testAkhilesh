<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function LoadTask() {
	$c="";

	if (ISSET($_GET['subtask'])) {
		$subtask_file=$GLOBALS['dr']."modules/visitors/modules/reports/".$_GET['subtask'].".php";
		if (file_exists($subtask_file)) {
			require_once $subtask_file;
			$c.=SubTask();
		}
		else {
			$c.="Sample report. To view similiar reports, please purchase our premium version to support this product.<br>\n";
			$c.="<img src='modules/visitors/images/reporting/".$_GET['subtask'].".png'>\n";
		}
	}
	else {
		/* LOAD THE LINKS */
		$c.="<table class='plain'>";
			$c.="<tr class='modulehead'>";
				$c.="<td colspan='2'>Reports</td>";
			$c.="</tr>";
			$c.="<tr>";
				$c.="<td><ul><li><a href='index.php?module=visitors&task=reports&subtask=checked_in_visitors'>Checked in visitors</a> - display all the visitors currently present</li></ul></td>\n";
				$c.="<td><img src='images/core/text.png'></td>\n";
			$c.="</tr>";
		$c.="</table>";
	}

	return $c;
}
?>