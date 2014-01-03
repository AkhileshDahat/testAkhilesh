<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function BrowseWorkspacesPublic() {
	$db=$GLOBALS['db'];
	$sql="SELECT wm.workspace_id, wm.workspace_code, wm.name, wm.max_teamspaces, wm.max_size, wm.max_users, wm.available_start_date,
				wm.available_end_date, wm.enterprise
				FROM ".$GLOBALS['database_prefix']."workspace_master wm
				ORDER BY wm.name
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	$s="<table class='plain_border' width='100%'>\n";
		$s.="<tr>\n";
			$s.="<td class='bold'>Workspaces</td>\n";
		$s.="</tr>\n";
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$s.="<tr onmouseover=\"this.className='alternateover'\" onMouseOut=\"this.className=''\">\n";
					$s.="<td><a href='index.php?module=workspace&task=show_workspace&workspace_id=".$row['workspace_id']."'>".$row['name']."</a></td>\n";
				$s.="</tr>\n";
			}
		}
	$s.="</table>\n";
	return $s;
}

?>