<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function LoadTask() {
	$c="";

	if (ISSET($_GET['subtask'])) {
		$subtask_file=$GLOBALS['dr']."modules/documents/modules/reports/".$_GET['subtask'].".php";
		if (file_exists($subtask_file)) {
			require_once $subtask_file;
			$c.=SubTask();
		}
	}
	else {
		/* LOAD THE LINKS */
		$c.="<table class='plain'>";
			$c.="<tr class='modulehead'>";
				$c.="<td>Reports</td>";
			$c.="</tr>";
			$c.="<tr>";
				$c.="<td><li><a href='index.php?module=documents&task=reports&subtask=category_file_size'>Category Growth</a> - this shows the size of all your categories</td>";
			$c.="</tr>";
			$c.="<tr>";
				$c.="<td><li><a href='index.php?module=documents&task=reports&subtask=locked_documents'>Locked Documents</a> - this shows all locked files</td>";
			$c.="</tr>";
		$c.="</table>";
	}

	return $c;
}
?>