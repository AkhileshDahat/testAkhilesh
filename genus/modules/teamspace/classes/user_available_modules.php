<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class UserAvailableModules {

	public function CountUserAvailableModules() {
		$db=$GLOBALS['db'];
		$sql="SELECT mm.module_id, mm.name, mm.logo
					FROM ".$GLOBALS['database_prefix']."core_space_modules tm, ".$GLOBALS['database_prefix']."core_module_master mm
					WHERE tm.workspace_id = ".$GLOBALS['workspace_id']."
					AND tm.teamspace_id ".$GLOBALS['teamspace_sql']."
					AND tm.module_id = mm.module_id
					AND tm.module_id NOT IN (
					  SELECT module_id
					  FROM ".$GLOBALS['database_prefix']."core_space_user_modules
					  WHERE user_id = '".$_SESSION['user_id']."'
					  AND teamspace_id ".$GLOBALS['teamspace_sql']."
					)
					ORDER BY mm.name
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		//echo "Test:".$db->NumRows($result)."<br>";
		return $db->NumRows($result);
	}

	public function ShowUserAvailableModules() {
		$db=$GLOBALS['db'];
		$sql="SELECT mm.module_id, mm.name, mm.logo
					FROM ".$GLOBALS['database_prefix']."core_space_modules tm, ".$GLOBALS['database_prefix']."core_module_master mm
					WHERE tm.teamspace_id = ".$GLOBALS['teamspace_id']."
					AND tm.module_id = mm.module_id
					AND mm.available_teamspaces = 'y'
					AND tm.module_id NOT IN (
					  SELECT module_id
					  FROM ".$GLOBALS['database_prefix']."core_space_user_modules
					  WHERE user_id = '".$_SESSION['user_id']."'
					  AND teamspace_id = ".$GLOBALS['teamspace_id']."
					)
					AND mm.module_id IN (
						SELECT module_id
						FROM ".$GLOBALS['database_prefix']."core_space_modules tm1
						WHERE tm1.workspace_id = ".$GLOBALS['workspace_id']."
					)
					ORDER BY mm.name
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		$t="<div id='slider'>\n";
		$t.="<table class=plain>\n";
			$t.="<tr>\n";
				$t.="<td colspan='2' class='bold'>Add Module</td>\n";
			$t.="</tr>\n";
			if ($db->NumRows($result) > 0) {
				while($row = $db->FetchArray($result)) {
					$safe_teamspace_name=STRTOLOWER(STR_REPLACE("_", " ",$row['name']));
					$t.="<tr>\n";
						$t.="<td><img src='modules/".$safe_teamspace_name."/images/default/icon_module16.png' width='16'></td>\n";
						$t.="<td><a href='index.php?wtask=install_teamspace_user_module&module_id=".$row['module_id']."'>".STR_REPLACE("_", " ",$row['name'])."</a></td><td></td>\n";
					$t.="</tr>\n";
				}
			}
			else {
				$t.="<tr><td colspan='4'>No available modules to install</td></tr>\n";
			}
		$t.="</table>\n";
		$t.="</div>\n";

		return $t;
	}

}
?>