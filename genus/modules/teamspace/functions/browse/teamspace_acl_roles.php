<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/acl/check_space_module_acl.php";

function TeamspaceACLRoles($module_id) {
	$db=$GLOBALS['db'];
	$sql="SELECT rm.role_id, rm.role_name
				FROM ".$GLOBALS['database_prefix']."core_role_master rm
				ORDER BY rm.role_name
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	$t="<table class=plain>\n";
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				if (CheckSpaceModuleACL($row['role_id'],$module_id,$task,$workspace_id,$teamspace_id)) {
				}
				$t.="<tr>\n";
					$t.="<td>".$row['role_name']."</td>\n";
					$t.="<td><input type=button onClick=document.location.href='index.php?module=teamspace&teamspace_acls&module_id=".$module_id."' value='".$but_val."'></td>\n";

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