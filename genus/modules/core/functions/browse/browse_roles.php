<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function BrowseRoles() {

	$sql="SELECT role_id,role_name,
							concat('<a href=index.php?module=core&task=roles&subtask=create_workspace&role_id=',role_id,' title=CreateWorkspace>',create_workspace,'</a>') as f1,
							concat('<a href=index.php?module=core&task=roles&subtask=manage_core_workspaces&role_id=',role_id,' title=ManageWorkspace>',manage_core_workspaces,'</a>') as f2,
							concat('<a href=index.php?module=core&task=roles&subtask=manage_core_users&role_id=',role_id,' title=ManageCoreUsers>',manage_core_users,'</a>') as f3,
							concat('<a href=index.php?module=core&task=roles&subtask=create_workspace_roles&role_id=',role_id,' title=ManageRoles>',create_workspace_roles,'</a>') as f3,
							concat('<a href=index.php?module=core&task=roles&subtask=edit&role_id=',role_id,' title=Browse>Edit</a>') as edit,
							concat('<a href=index.php?module=core&task=roles&subtask=delete&role_id=',role_id,' title=Browse>Delete</a>') as del
							FROM ".$GLOBALS['database_prefix']."core_role_master
							ORDER BY role_name
							";

	$sql = str_replace("\n","",$sql);
	//$sql = str_replace("  ","",$sql);
	$sql = str_replace("\t","",$sql);
	//$sql = str_replace(Chr(13),"",$sql);
	//echo $sql;
	$_SESSION['ex2'] = $sql;

	$head = $GLOBALS['head'];
	$head->IncludeFile("rico21");

	$c="";

	$c.="<p class='ricoBookmark'><span id='ex2_timer' class='ricoSessionTimer'></span><span id='ex2_bookmark'>&nbsp;</span></p>\n";

	$c.="<table id='ex2' class='ricoLiveGrid' cellspacing='0' cellpadding='0'>\n";
	$c.="<colgroup>\n";
	$c.="<col style='width:40px;' >\n";
	$c.="<col style='width:150px;' >\n";
	$c.="<col style='width:50px;' >\n";
	$c.="<col style='width:50px;' >\n";
	$c.="<col style='width:90px;' >\n";
	$c.="<col style='width:50px;' >\n";
	$c.="<col style='width:50px;' >\n";
	$c.="<col style='width:50px;' >\n";
	$c.="</colgroup>\n";
	  $c.="<tr>\n";
		  $c.="<th>ID</th>\n";
		  $c.="<th>Name</th>\n";
		  $c.="<th>CreateWS</th>\n";
		  $c.="<th>ManageWS</th>\n";
		  $c.="<th>Manage Users</th>\n";
		  $c.="<th>ManageRoles</th>\n";
		  $c.="<th>Edit</th>\n";
		  $c.="<th>Delete</th>\n";
	  $c.="</tr>\n";
	$c.="</table>\n";
	return $c;

}

?>