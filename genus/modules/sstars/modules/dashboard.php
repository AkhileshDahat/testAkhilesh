<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

echo "<table>\n";
	echo "<tr>\n";
		echo "<td>".CurveBox("<img src='".$wb."modules/sstars/dashboard/graphs/yearly_expense.php?width=200&height=200' width='200' height='200'>")."</td>\n";
		echo "<td></td>\n";
		echo "<td></td>\n";
	echo "</tr>\n";
echo "</table>\n";

?>