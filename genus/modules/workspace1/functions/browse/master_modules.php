<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function MasterModules() {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Logo","Name","Description","Available Teamspace")); /* COLS */
	$sr->Columns(array("module_id","logo","name","description","available_teamspaces"));
	$sr->Query("SELECT mm.module_id,mm.logo,mm.name,mm.description,mm.available_teamspaces,mm.signup_module
							FROM ".$GLOBALS['database_prefix']."core_module_master mm
							ORDER BY mm.name");

	$sr->ColDefault(4,"yesnoimage"); /* SET POPUP TO YES/NO */
	for ($i=0;$i<$sr->CountRows();$i++) {
		$module_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT LINES */
		$module_name=STRTOLOWER($sr->GetRowVal($i,2)); /* FASTER THAN CALLING EACH TIME IN THE NEXT LINES */
		$logo=$sr->GetRowVal($i,1); /* FASTER THAN CALLING EACH TIME IN THE NEXT LINES */
		$name=$sr->GetRowVal($i,2); /* FASTER THAN CALLING EACH TIME IN THE NEXT LINES */
		$available_teamspaces=$sr->GetRowVal($i,4); /* FASTER THAN CALLING EACH TIME IN THE NEXT LINES */

		$sr->ModifyData($i,1,"<img src='modules/".$module_name."/images/default/icon_module.png' width='28'>");
		//$sr->ModifyData($i,1,"<img src='images/modules/".$module_name.".png' width='28'>");
		//$sr->ModifyData($i,2,"<a href='index.php?module=workspace&task=master_modules&subtask=edit&module_id=".$module_id."&jshow=add' title='Click to edit modules'>".$name."</a>");
		//$sr->ModifyData($i,3,"<a href='index.php?module=workspace&task=add_module&module_id=".$module_id."' title='Click to edit modules'>".$available_teamspaces."</a>"); /* CHANGE THE AVAILABLE TEAMSPACES TO A LINK TO ENABLE / DISABLE */

	}
	$sr->RemoveColumn(0);
	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Browsing system installed modules");
	return $sr->Draw();
}

?>