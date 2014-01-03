<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function BrowseWorkspaceModuleRoles($workspace_id,$module_id) {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Name","Installed")); /* COLS */
	$sr->Columns(array("role_id","role_name","installed"));

	$sql = "SELECT DISTINCT cwrm.role_id,crm.role_name,CASE WHEN csma.module_id IS NULL THEN 'No' ELSE 'Yes' END AS installed
					FROM ".$GLOBALS['database_prefix']."core_role_master crm, ".$GLOBALS['database_prefix']."core_workspace_role_master cwrm
						LEFT JOIN ".$GLOBALS['database_prefix']."core_space_module_acl csma
						ON cwrm.role_id = csma.role_id
						AND csma.module_id = '".$module_id."'
					WHERE cwrm.workspace_id = ".$workspace_id."
					AND cwrm.role_id = crm.role_id
							";
	//echo $sql;
	$sr->Query($sql);

	for ($i=0;$i<$sr->CountRows();$i++) {
		$installed=$sr->GetRowVal($i,2); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$role_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$sr->ModifyData($i,2,"<a href='index.php?module=core&task=workspace_module_roles&subtask=grant_access&workspace_id=".$workspace_id."&module_id=".$module_id."&role_id=".$role_id."&installed=".STRTOLOWER($installed)."' title='Click to change access'>".$installed."</a>");
	}
	$sr->RemoveColumn(0);
	//$sr->RemoveColumn(1);

	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Browsing workspace module role access");

	$sr->FooterLinks("index.php?module=core&task=workspace_modules&workspace_id=".$workspace_id,"Back");
	$sr->CompileFooterLinks();
	return $sr->Draw();

}

?>