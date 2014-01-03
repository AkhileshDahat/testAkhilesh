<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function InstalledWorkspaceModules($workspace_id) {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Logo","Name","Description","Role Access","Remove")); /* COLS */
	$sr->Columns(array("module_id","logo","name","description","roles","remove"));

	$sql="SELECT csm.module_id, mm.logo,mm.name,mm.description,'roles' AS roles,'remove' AS remove
							FROM ".$GLOBALS['database_prefix']."core_space_modules csm, ".$GLOBALS['database_prefix']."core_module_master mm
							WHERE csm.workspace_id = ".EscapeData($workspace_id)."
							AND csm.teamspace_id IS NULL
							AND csm.module_id = mm.module_id
							ORDER BY mm.name";

	$sr->Query($sql);
	//$sr->ColDefault(8,"yesnoimage"); /* SET POPUP TO YES/NO */

	for ($i=0;$i<$sr->CountRows();$i++) {
		$module_id=STRTOLOWER($sr->GetRowVal($i,0)); /* FASTER THAN CALLING EACH TIME IN THE NEXT LINES */
		$module_name=STRTOLOWER($sr->GetRowVal($i,2)); /* FASTER THAN CALLING EACH TIME IN THE NEXT LINES */
		$logo=$sr->GetRowVal($i,1); /* FASTER THAN CALLING EACH TIME IN THE NEXT LINES */

		$sr->ModifyData($i,1,"<img src='modules/".$module_name."/images/default/icon_module.png' width='28'>");
		$sr->ModifyData($i,4,"<a href='index.php?module=workspace&task=workspace_module_roles&workspace_id=".$workspace_id."&module_id=".$module_id."' title='Click to view role access'>View</a>");
		$sr->ModifyData($i,5,"<a href='index.php?module=workspace&task=installed_workspace_modules&subtask=remove&workspace_id=".$workspace_id."&module_id=".$module_id."' title='Click to remove module'>Remove</a>");

	}
	$sr->RemoveColumn(0);
	$sr->RemoveColumn(3);
	//$sr->AddCell("hello");

	$sr->WrapData();
	$sr->Footer();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Browsing installed workspace modules");
	return $sr->Draw();
}

?>