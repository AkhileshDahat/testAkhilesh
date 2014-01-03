<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

echo Summary(EscapeData($_GET['workspace_id']));

function Summary($workspace_id) {
	$db=$GLOBALS['db'];

	$sql="SELECT wm.workspace_code, wm.name, wm.max_teamspaces, wm.max_size, wm.max_users,
				wm.date_start, wm.date_end, wm.enterprise
				FROM ".$GLOBALS['database_prefix']."core_workspace_master wm
				WHERE wm.workspace_id = '".$workspace_id."'
				ORDER BY wm.name
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	$s="<table class='plain_border'>\n";
		$s.="<tr>\n";
			$s.="<td class='colhead' colspan='10'>Workspace Summary</td>\n";
		$s.="</tr>\n";
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$s.="<tr>\n";
					$s.="<td class='bold'>Code</td>\n";
					$s.="<td>".$row['workspace_code']."</td>\n";
				$s.="</tr>\n";
				$s.="<tr>\n";
					$s.="<td class='bold'>Name</td>\n";
					$s.="<td>".$row['name']."</td>\n";
				$s.="</tr>\n";
				$s.="<tr>\n";
					$s.="<td class='bold'>Max Teamspaces</td>\n";
					$s.="<td>".$row['max_teamspaces']."</td>\n";
				$s.="</tr>\n";
				$s.="<tr>\n";
					$s.="<td class='bold'>Max Size</td>\n";
					$s.="<td>".$row['max_size']."</td>\n";
				$s.="</tr>\n";
				$s.="<tr>\n";
					$s.="<td class='bold'>Max Users</td>\n";
					$s.="<td>".$row['max_users']."</td>\n";
				$s.="</tr>\n";
				$s.="<tr>\n";
					$s.="<td class='bold'>Start Date</td>\n";
					$s.="<td>".$row['date_start']."</td>\n";
				$s.="</tr>\n";
				$s.="<tr>\n";
					$s.="<td class='bold'>End Date</td>\n";
					$s.="<td>".$row['date_end']."</td>\n";
				$s.="</tr>\n";
				$s.="<tr>\n";
					$s.="<td class='bold'>Enterprise</td>\n";
					$s.="<td>".$row['enterprise']."</td>\n";
				$s.="</tr>\n";
			}
		}
	$s.="</table>\n";

	return $s;
}
?>