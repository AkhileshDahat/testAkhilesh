<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

echo "<table>\n";
	echo "<form method='post' action='index.php?module=user_directory'>\n";
	echo "<tr>\n";
		echo "<td colspan='2'>User Directory Search</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
		echo "<td>Username:</td>\n";
		echo "<td><input type='text' name='username' size='15'></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
		echo "<td colspan='2'><input type='submit' value='search'></td>\n";
	echo "</tr>\n";
	echo "</form>\n";
echo "</table>\n";
?>