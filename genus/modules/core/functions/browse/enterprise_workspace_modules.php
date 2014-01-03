<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."modules/workspace/functions/acl/workspace_module_role_exists.php";

function EnterpriseWorkspaceModules() {

	$db=$GLOBALS['db']; /* DATABASE CONNECTION */
	$wb=$GLOBALS['wb']; /* WEBSITE URL */

	if (!defined( '_VALID_MVH_MOBILE_' )) {
		$break_after=5;
		$mobile=false;
	}
	else {
		$break_after=4;
		$mobile=true;
	}
	if ($GLOBALS['teamspace_id'] == 0) {
		$header="Workspace";
		$sql="SELECT mm.module_id, mm.name, mm.logo
					FROM ".$GLOBALS['database_prefix']."core_space_user_modules wum, ".$GLOBALS['database_prefix']."core_module_master mm
					WHERE wum.workspace_id = ".$GLOBALS['workspace_id']."
					AND wum.user_id = ".$_SESSION['user_id']."
					AND wum.module_id = mm.module_id
					ORDER BY wum.ordering
					";
	}
	else {
		$header="Teamspace";
		$sql="SELECT mm.module_id, mm.name, mm.logo
					FROM ".$GLOBALS['database_prefix']."core_space_user_modules tum, ".$GLOBALS['database_prefix']."core_module_master mm
					WHERE tum.teamspace_id ".$GLOBALS['teamspace_sql']."
					AND tum.user_id = ".$_SESSION['user_id']."
					AND tum.module_id = mm.module_id
					ORDER BY tum.ordering
					";
	}
	//echo $sql."<br>";
	$result = $db->Query($sql);
	$s="<table class='plain' bgcolor='#F7F8FB' cellpadding='0' cellspacing='0' align='center' valign='top'>\n";
		$s.="<tr>\n";
			$s.="<td bgcolor='#E4E9F4' class='colhead' align='center'>Your ".$header."</td>\n";
		$s.="</tr>\n";
		$s.="<tr>\n";
			$s.="<td>\n";
			$s.="<table class='teamspace' cellpadding='0' cellspacing='5'>\n";
				if ($db->NumRows($result) > 0) {
					while($row = $db->FetchArray($result)) {
						if (WorkspaceModuleRoleExists($GLOBALS['workspace_id'],$row['module_id'],$GLOBALS['wui']->RoleID())) { /* CHECK THE ROLE FOR THE MODULE */
							$s.="<tr>\n";
							$desc=STR_REPLACE("_", " ",$row['name']);
							$desc=InitCapAllWords($desc);
							$s.="<td><img src='modules/".STRTOLOWER($row['name'])."/images/default/icon_module16.png' width=16></td>\n";
							$s.="<td><a href='index.php?module=".STRTOLOWER($row['name'])."&task=home'>".$desc."</td>\n";
							$s.="</tr>\n";
						}
					}
				}
			$s.="</table>\n";
			$s.="</td>\n";
		$s.="</tr>\n";
	$s.="</table>\n";
	return $s;
}

/*
function InitCapAllWords($v) {
	$first_split=explode(" ",$v);
	$c="";
	for($i=0;$i<count($first_split);$i++) {
		$c.=" ".STRTOUPPER(SUBSTR($first_split[$i], 0, 1)).SUBSTR($first_split[$i], 1);
	}
	return $c;
}
*/
?>