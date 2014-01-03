<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function TeamspaceSliderItems() {
	$db=$GLOBALS['db'];
	$sql="SELECT DISTINCT mm.module_id, mm.name, mm.logo
				FROM ".$GLOBALS['database_prefix']."core_space_module_acl cma, ".$GLOBALS['database_prefix']."core_module_master mm
				WHERE cma.workspace_id = ".$GLOBALS['workspace_id']."
				AND cma.role_id = '".$GLOBALS['wui']->role_id."'
				AND cma.module_id = mm.module_id
				AND cma.module_id NOT IN (
				  SELECT module_id
				  FROM ".$GLOBALS['database_prefix']."core_space_user_modules
				  WHERE user_id = '".$_SESSION['user_id']."'
				  AND workspace_id = ".$GLOBALS['workspace_id']."
				)
				ORDER BY mm.name
				";
	//echo "ok";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	$t="<div id='slider'>\n";
	$t.="<table class=plain>\n";
		$t.="<tr>\n";
			$t.="<td colspan='2' class='bold'>Add Components</td>\n";
		$t.="</tr>\n";
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$module_name=STRTOLOWER(STR_REPLACE("_", " ",$row['name']));
				$t.="<tr>\n";
					$t.="<td><img src='modules/".$module_name."/images/default/icon_module.png' width='22'></td>\n";
					$t.="<td><a href='index.php?wtask=install_workspace_user_module&module_id=".$row['module_id']."'>".STR_REPLACE("_", " ",$row['name'])."</a></td><td></td>\n";
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
?>