<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/workspace/functions/forms/add_module.php";

function Add() {

	if (ISSET($_GET['subtask']) && $_GET['subtask']=="edit" && ISSET($_GET['module_id']) && IS_NUMERIC($_GET['module_id'])) {
		require_once $GLOBALS['dr']."modules/workspace/classes/master_modules.php";
		$mm=new MasterModules;
		$mm->Info($_GET['module_id']);
		$module_id=$_GET['module_id'];
		$name=$mm->GetInfo("name");
		$description=$mm->GetInfo("description");
		$available_teamspaces=$mm->GetInfo("available_teamspaces");
		if ($available_teamspaces=="y") { $available_teamspaces=True; } else { $available_teamspaces=False; }
		$logo=$mm->GetInfo("logo");
		$signup_module=$mm->GetInfo("signup_module");
		if ($signup_module=="y") { $signup_module=True; } else { $signup_module=False; }
	}
	else {
		$module_id="";
		$name="";
		$description="";
		$available_teamspaces="";
		$logo="";
		$signup_module="";
	}

	return CurveBox(AddModule($module_id,$name,$description,$available_teamspaces,$logo,$signup_module));

}
?>