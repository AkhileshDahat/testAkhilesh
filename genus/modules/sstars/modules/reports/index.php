<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

echo CurveBox("<img src='images/nuvola/32x32/actions/run.png'>Reports");

echo "<table class='plain_border' width='100%'>\n";
	echo "<tr>\n";
		echo "<td><a href='index.php?module=sstars&task=reports&sub=my_outstanding_orders'>My Outstanding Orders</a></td>\n";
		echo "<td class=''>This report shows you all you outstanding orders which have not been confirmed</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
		echo "<td><a href='index.php?module=sstars&task=reports&sub=my_pending_approval_orders'>Orders Pending My Approval</a></td>\n";
		echo "<td class=''>This report shows you all you outstanding orders which you need to approve</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
		echo "<td><a href='index.php?module=sstars&task=reports&sub=available_items'>Available Items</a></td>\n";
		echo "<td class=''>This report shows you all you available stock</td>\n";
	echo "</tr>\n";
echo "</table>\n";

?>