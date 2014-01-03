<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($dr."modules/groups/config.php");
require_once($dr."modules/groups/functions/menu.php");

echo "<table>\n";
	echo "<tr>\n";
		echo "<td width='90%'>\n";
		if (EMPTY($_GET['task'])) {
			require_once($dr."modules/groups/modules/home.php");
		}
		else {
			$file_inc=$dr."modules/groups/modules/".$_GET['task'].".php";
			if (file_exists($file_inc)) {
				require_once($file_inc);
			}
		}
		echo "</td>\n";
		echo "<td width='150' valign='top'>\n";
		echo CurveBox(Menu($user_id));
		echo "</td>\n";
	echo "</tr>\n";
echo "</table>\n";
?>