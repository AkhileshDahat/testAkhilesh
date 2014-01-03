<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/workspace/classes/add_remove_module.php";
require_once $GLOBALS['dr']."modules/workspace/functions/browse/installed_workspace_modules.php";

function LoadTask() {

	if (ISSET($_GET['subtask'])) {
		if ($_GET['subtask']=="remove") {
			//echo "Removing module from workspace<br>";
			$arm=new AddRemoveModule;
			$arm->SetParameters($_GET['workspace_id'],$_GET['module_id']);
			$result=$arm->DeleteWorkspaceModule();
			if ($result) { echo "success"; } else { echo $arm->ShowErrors(); }
		}
	}
	return InstalledWorkspaceModules($_GET['workspace_id']);
}

?>