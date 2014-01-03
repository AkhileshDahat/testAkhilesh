<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/teamspace/functions/browse/teamspace_acl_roles.php";

function TeamspaceACLs() {
	$db=$GLOBALS['db'];
	$sql="SELECT mm.module_id, mm.logo, mm.name
				FROM ".$GLOBALS['database_prefix']."core_module_master mm, ".$GLOBALS['database_prefix']."core_space_modules cm
				WHERE mm.available_teamspaces = 'y'
				AND mm.module_id = cm.module_id
				AND cm.workspace_id = ".$GLOBALS['workspace_id']."
				GROUP BY mm.module_id
				ORDER BY mm.name
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	$t="<table class=plain>\n";
		$t.="<tr>\n";
			$t.="<td colspan='2' class='bold'>Available Modules</td>\n";
		$t.="</tr>\n";
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$t.="<tr>\n";
					$t.="<td><img src='images/".$row['logo']."' width='22'></td>\n";
					$t.="<td>".$row['name']."</td>\n";
					$t.="<td>".TeamspaceACLRoles($row['module_id'])."</td>\n";
				$t.="</tr>\n";
			}
		}
		else {
			$t.="<tr><td colspan='4'>No available modules for teamspace</td></tr>\n";
		}
	$t.="</table>\n";
	return $t;
}
?>