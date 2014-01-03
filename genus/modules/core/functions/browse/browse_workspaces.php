<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function BrowseWorkspaces($user_id) {

	//unset($_SESSION['ex2']);
	//echo "ok";
	/*

	*/
	$sql_extra = "";
	if ($GLOBALS['ui']->GetInfo("manage_core_workspaces") == "n") {
		$sql_extra = " WHERE user_id_added = ".$_SESSION['user_id'];
	}

	$sql = "SELECT workspace_code, date_start,
						concat('<a href=index.php?module=core&task=available_workspace_modules&workspace_id=',workspace_id,'>Modules</a>') as domodules,
						concat('<a href=index.php?module=core&task=workspace_modules&workspace_id=',workspace_id,'>Permissions</a>') as insmodules,
						concat('<a href=index.php?module=core&task=available_workspace_roles&workspace_id=',workspace_id,'>Roles</a>') as roles,
						concat('<a href=index.php?module=core&task=available_workspace_users&workspace_id=',workspace_id,'>Users</a>') as users,
						concat('<a href=index.php?module=core&task=workspace_user_roles&workspace_id=',workspace_id,'>User Roles</a>') as userroles,
						concat('<a href=index.php?module=core&task=workspace_user_modules&workspace_id=',workspace_id,'>User Modules</a>') as usermods,
						concat('<a href=index.php?module=core&task=add_workspace&subtask=edit&workspace_id=',workspace_id,'>Edit</a>') as doedit,
						concat('<a href=index.php?module=core&task=browse_workspaces&subtask=del&workspace_id=',workspace_id,'>Delete</a>') as dodelete
					FROM core_workspace_master
					$sql_extra
					ORDER BY workspace_id DESC
					";

	$sql = str_replace("\n"," ",$sql);
	//$sql = str_replace("  ","",$sql);
	$sql = str_replace("\t","",$sql);
	//$sql = str_replace(Chr(13),"",$sql);
	//echo $sql;
	$_SESSION['ex2'] = $sql;

	$head = $GLOBALS['head'];
	$head->IncludeFile("rico2rc2");

	$c="

	<p class='ricoBookmark'><span id='ex2_timer' class='ricoSessionTimer'></span><span id='ex2_bookmark'>&nbsp;</span></p>
	<table id='ex2' class='ricoLiveGrid' cellspacing='0' cellpadding='0'>
	<colgroup>
	<col style='width:50px;' >
	<col style='width:70px;' >
	<col style='width:70px;' >
	<col style='width:90px;' >
	<col style='width:50px;' >
	<col style='width:60px;' >
	<col style='width:100px;' >
	<col style='width:60px;' >
	<col style='width:60px;' >
	</colgroup>
	  <tr>
		  <th>CODE</th>
		  <th>VALID TO</th>
		  <th>MODULES</th>
		  <th>PERMISSIONS</th>
		  <th>ROLES</th>
		  <th>USERS</th>
		  <th>USER ROLES</th>
		  <th>USER MODULES</th>
		  <th>EDIT</th>
		  <th>REMOVE</th>
	  </tr>
	</table>
	";

	return $c;

}

?>