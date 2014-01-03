<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function AvailableWorkspaceRoles($workspace_id) {

	$sql="SELECT crm.role_name,
					IF(cwrm.role_id IS NULL,
						concat('<a href=index.php?module=core&task=available_workspace_roles&subtask=install&workspace_id=$workspace_id&role_id=',crm.role_id,'>INSTALL</a>'),
						concat('<a href=index.php?module=core&task=available_workspace_roles&subtask=remove&workspace_id=$workspace_id&role_id=',crm.role_id,'>REMOVE</a>'))
				FROM ".$GLOBALS['database_prefix']."core_role_master crm
				LEFT JOIN ".$GLOBALS['database_prefix']."core_workspace_role_master cwrm
				ON crm.role_id = cwrm.role_id
				AND cwrm.workspace_id = '".EscapeData($workspace_id)."'
				ORDER BY crm.role_name
				";

	$sql = str_replace("\n"," ",$sql);
	$sql = str_replace("\t","",$sql);
	//echo $sql;
	$_SESSION['ex2'] = $sql;
	$head = $GLOBALS['head'];
	$head->IncludeFile("rico2rc2");

	$c="

	<p class='ricoBookmark'><span id='ex2_timer' class='ricoSessionTimer'></span><span id='ex2_bookmark'>&nbsp;</span></p>
	<table id='ex2' class='ricoLiveGrid' cellspacing='0' cellpadding='0'>
	<colgroup>
	<col style='width:250px;' >
	<col style='width:100px;' >
	</colgroup>
	  <tr>
		  <th>NAME</th>
		  <th>STATUS</th>
	  </tr>
	</table>
	";

	return $c;
}
?>