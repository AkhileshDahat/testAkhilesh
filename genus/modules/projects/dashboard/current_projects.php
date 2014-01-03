<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

$sql="SELECT pm.project_id, pm.title
			FROM mvh_projects.mvh_project_master pm
			WHERE pm.teamspace_id = '".$teamspace_id."'
			";
//echo $sql;
$result = $db->Query($sql);
echo "<table class='plain'>\n";
	echo "<tr>\n";
		echo "<td colspan='2'>Teamspace Projects</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	if ($db->NumRows($result) > 0) {
		while($row = $db->FetchArray($result)) {
			echo "<td>".$row['title']."</td>\n";
			echo "<td><a href='index.php?module=projects&project_id=".$row['project_id']."'>View</a></td>\n";
		}
		echo "</tr>\n";
	}
echo "</table>\n";

?>