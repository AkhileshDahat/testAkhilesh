<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function AvailableWorkspaceModules($workspace_id) {

	$sql="SELECT cmm.name, cmm.description,
					IF(csm.datetime_installed IS NULL,
						concat('<a href=index.php?module=core&task=available_workspace_modules&subtask=install&workspace_id=$workspace_id&module_id=',cmm.module_id,'>INSTALL</a>'),
						concat('<a href=index.php?module=core&task=available_workspace_modules&subtask=remove&workspace_id=$workspace_id&module_id=',cmm.module_id,'>REMOVE</a>'))
				FROM ".$GLOBALS['database_prefix']."core_module_master cmm
					LEFT JOIN ".$GLOBALS['database_prefix']."core_space_modules csm
					ON cmm.module_id = csm.module_id
					AND csm.workspace_id = '".EscapeData($workspace_id)."'
				ORDER BY cmm.name
				";

	$sql = str_replace("\n"," ",$sql);
	$sql = str_replace("\t","",$sql);
	//echo $sql."<br />";
	$_SESSION['ex2'] = $sql;
	$head = $GLOBALS['head'];
	$head->IncludeFile("rico2rc2");

	$c="

	<p class='ricoBookmark'><span id='ex2_timer' class='ricoSessionTimer'></span><span id='ex2_bookmark'>&nbsp;</span></p>
	<table id='ex2' class='ricoLiveGrid' cellspacing='0' cellpadding='0'>
	<colgroup>
	<col style='width:150px;' >
	<col style='width:350px;' >
	<col style='width:100px;' >
	</colgroup>
	  <tr>
		  <th>NAME</th>
		  <th>DESCRIPTION</th>
		  <th>STATUS</th>
	  </tr>
	</table>
	";

	return $c;
}
?>