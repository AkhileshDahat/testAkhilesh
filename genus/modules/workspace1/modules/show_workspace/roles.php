<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."include/functions/db/row_exists.php";
require_once $dr."modules/workspace/classes/module_acl.php";
//require_once $dr."include/functions/db/get_col_value.php";

if ($_GET['acl_role_id']) {
	$macl=new ModuleACL;

	$macl->SetParameters(EscapeData($_GET['workspace_id']),EscapeData($_GET['module']),EscapeData($_GET['acl_role_id']));
	$role_result=$macl->AddRemoveRole();

	if ($role_result) {
		echo Alert(36);
	}
	else {
		echo Alert(37,$macl->ShowErrors());
	}
}


echo ACL(EscapeData($_GET['workspace_id']),EscapeData($_GET['module_id']));

function ACL($workspace_id,$module_id) {

	if (EMPTY($workspace_id) || EMPTY($module_id)) { return "Please choose a module from the module tab"; }
	$db=$GLOBALS['db'];

	$sql="SELECT crm.role_id, crm.role_name
				FROM ".$GLOBALS['database_prefix']."core_role_master crm
				WHERE crm.workspace_id = '".$workspace_id."'
				ORDER BY crm.role_name
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	$s="<table class='plain_border'>\n";
		$s.="<tr>\n";
			$s.="<td class='colhead' colspan='10'>Role Access for Module '".GetColumnValue("name","core_module_master","module",$module_name,"")."'</td>\n";
		$s.="</tr>\n";
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				if (RowExists("core_space_module_acl","workspace_id",$workspace_id,"AND module_id = '".$module_id."' AND role_id = '".$row['role_id']."'")) {
					$module_exists="Yes";
				}
				else {
					$module_exists="No";
				}
				$s.="<tr onmouseover=\"this.className='alternateover'\" onMouseOut=\"this.className=''\">\n";
					$s.="<td class='bold'>".$row['role_name']."</td>\n";
					$s.="<td><a href='index.php?module=workspace&task=show_workspace&workspace_id=".$workspace_id."&module_name=".$module_name."&acl_role_id=".$row['role_id']."&block_show=roles'>".$module_exists."</a></td>\n";
				$s.="</tr>\n";
			}
		}
	$s.="</table>\n";

	return $s;
}
?>