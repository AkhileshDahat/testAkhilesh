<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function UserTeamSpaces() {
	$ui=$GLOBALS['ui'];
	$wui=$GLOBALS['wui'];
	$db=$GLOBALS['db'];
	$sql="SELECT tm.teamspace_id, tm.name, tm.icon
				FROM ".$GLOBALS['database_prefix']."core_teamspace_master tm, ".$GLOBALS['database_prefix']."core_space_module_acl ta
				WHERE tm.workspace_id = ".$ui->WorkspaceID()."
				AND tm.teamspace_id = ta.teamspace_id
				AND ta.role_id = '".$GLOBALS['wui']->RoleID()."'
				ORDER BY tm.name
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	$s="<table class='plain' width='150'>\n";
		$s.="<tr>\n";
			$s.="<td class='bold' colspan='2'>"._TEAMSPACES_MENU_."</td>\n";
		$s.="</tr>\n";
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$s.="<tr>\n";
					$s.="<td align='center'><img src='images/".$row['icon']."' border='0'></td>\n";
					//$s.="<td><a href='bin/teamspace/activate.php?teamspace_id=".$row['teamspace_id']."'>".STR_REPLACE("_", " ",$row['name'])."</a></td>\n";
					$s.="<td><a href='index.php?dtask=activate_teamspace&teamspace_id=".$row['teamspace_id']."'>".STR_REPLACE("_", " ",$row['name'])."</a></td>\n";
				$s.="</tr>\n";
			}
		}
		else {
			$s.="<tr>\n";
				$s.="<td colspan='2'><a href='index.php?module=teamspace&task=new_teamspace'>"._TEAMSPACES_CREATE_."</a></td>\n";
			$s.="</tr>\n";
		}
		$s.="<tr>\n";
			$s.="<td colspan='2'><hr></td>\n";
		$s.="</tr>\n";
		$s.="<tr>\n";
			$s.="<td colspan='2' class='bold'>"._WORKSPACE_INFO_."</td>\n";
		$s.="</tr>\n";
		$s.="<tr>\n";
			$s.="<td colspan='2'>"._TEAMSPACES_YOUR_ROLE_." ".$wui->RoleName()."</td>\n";
		$s.="</tr>\n";
	$s.="</table>\n";
	return $s;
}
?>