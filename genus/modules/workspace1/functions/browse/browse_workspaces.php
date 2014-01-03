<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function BrowseWorkspaces($user_id) {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Code","Name","Start","End","Enterprise","Available Modules","Installed Modules","Edit","Delete")); /* COLS */
	$sr->Columns(array("workspace_id","workspace_code","name","date_start","date_end","enterprise","available_modules","installed_modules","edit_record","delete_record"));
	$sr->Query("SELECT wm.workspace_id,wm.workspace_code,wm.name,wm.date_start,wm.date_end,wm.enterprise,'available modules' AS available_modules,'installed modules' AS installed_modules,'edit_record' AS edit_record,'delete_record' AS delete_record
							FROM ".$GLOBALS['database_prefix']."core_workspace_master wm, ".$GLOBALS['database_prefix']."core_space_status_master wsm,
							".$GLOBALS['database_prefix']."core_space_users wu, ".$GLOBALS['database_prefix']."core_workspace_role_master cwrm
							WHERE wm.status_id = wsm.status_id
							AND wsm.is_active = 'y'
							AND wm.workspace_id = wu.workspace_id
							AND wu.user_id = ".$_SESSION['user_id']."
							AND wu.role_id = cwrm.role_id
							AND cwrm.manage_workspaces = 'y'
							ORDER BY wm.name");
	//echo $_SESSION['user_id'];
	for ($i=0;$i<$sr->CountRows();$i++) {
		$workspace_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$sr->ModifyData($i,6,"<a href='index.php?module=workspace&task=available_workspace_modules&workspace_id=".$workspace_id."' title='Click to view modules'>Available Modules</a>");
		$sr->ModifyData($i,7,"<a href='index.php?module=workspace&task=installed_workspace_modules&workspace_id=".$workspace_id."' title='Click to view modules'>Installed Modules</a>");
		$sr->ModifyData($i,8,"<a href='index.php?module=workspace&task=add_workspace&workspace_id=".$workspace_id."' title='Click to edit this item'>Edit</a>");
		$sr->ModifyData($i,9,"<a href='index.php?module=workspace&task=browse_workspaces&subtask=delete_workspace&workspace_id=".$workspace_id."' title='Click to delete this item'>Delete</a>");
	}
	$sr->RemoveColumn(0);
	$sr->ColDefault(5	,"yesnoimage"); /* SET POPUP TO YES/NO */

	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Browsing workspaces");
	return $sr->Draw();


	//$db=$GLOBALS['db'];

	/* DELETE WORKSPACE */
	/*if (ISSET($_GET['delete_workspace'])) {
		require_once $dr."modules/workspace/classes/delete_workspace.php";
		$workspace_id=EscapeData($_GET['workspace_id']);
		$dw=new DeleteWorkspace($workspace_id,$GLOBALS['user_id']);
	}
	*/


}

?>