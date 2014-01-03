<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function AvailableWorkspaceModules($workspace_id) {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Name","Description","","Install")); /* COLS */
	$sr->Columns(array("module_id","name","description","logo","install"));
	$sr->Query("SELECT mm.module_id,mm.name,mm.description,mm.logo,'install' AS install
							FROM ".$GLOBALS['database_prefix']."core_module_master mm
							WHERE mm.available_all_workspaces = 'y'
							AND mm.module_id NOT IN (
								SELECT module_id
								FROM ".$GLOBALS['database_prefix']."core_space_modules wm
								WHERE wm.workspace_id = ".EscapeData($workspace_id)."
							)
							ORDER BY mm.name
							");
	//$sr->ColDefault(8,"yesnoimage"); /* SET POPUP TO YES/NO */

	for ($i=0;$i<$sr->CountRows();$i++) {
		$module_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT LINES */
		$module_name=STRTOLOWER($sr->GetRowVal($i,2)); /* FASTER THAN CALLING EACH TIME IN THE NEXT LINES */
		$logo=$sr->GetRowVal($i,3); /* FASTER THAN CALLING EACH TIME IN THE NEXT LINES */

		$sr->ModifyData($i,4,"<a href='index.php?module=workspace&task=available_workspace_modules&subtask=install&module_id=".$module_id."&workspace_id=".$workspace_id."' title='Click to install modules'>Install</a>");
		$sr->ModifyData($i,1,"<img src='modules/".$module_name."/images/default/icon_module.png' width='28'>");

	}
	$sr->RemoveColumn(3);
	//$sr->AddCell("hello");
	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Browsing available workspace modules");
	return $sr->Draw();
}

?>