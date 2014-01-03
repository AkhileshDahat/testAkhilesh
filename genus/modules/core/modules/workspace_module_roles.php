<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/core/functions/browse/browse_workspace_module_roles.php";

function LoadTask() {

	$c="";

	/* SUBTASKS */
	if (ISSET($_GET['subtask'])) {
		if ($_GET['subtask']=="grant_access") {
			require_once $GLOBALS['dr']."modules/core/classes/module_acl.php";
			$ma=new ModuleACL;
			$ma->SetParameters($_GET['workspace_id'],$_GET['module_id'],$_GET['role_id']);
			if ($_GET['installed']=="yes") {
				$result=$ma->DeleteWorkspaceModuleRole();
			}
			else {
				$result=$ma->AddWorkspaceModuleRole();
			}
			if ($result) {
				$GLOBALS['errors']->SetAlert("Success, changed role for workspace");
			}
			else {
				$GLOBALS['errors']->SetError($ma->ShowErrors());
			}

		}
	}

	/* BROWSING THE MODULE ROLES */
	$c.=BrowseWorkspaceModuleRoles($_GET['workspace_id'],$_GET['module_id']);

	return $c;
}
?>