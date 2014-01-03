<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function WorkspaceModules($workspace_id) {

	$sql="SELECT mm.name,mm.description,concat('<a href=index.php?module=core&task=workspace_module_roles&subtask=del&workspace_id=',workspace_id,'&module_id=',csm.module_id,'>Permissions</a>') as doedit
				FROM ".$GLOBALS['database_prefix']."core_space_modules csm, ".$GLOBALS['database_prefix']."core_module_master mm
				WHERE csm.workspace_id = ".EscapeData($workspace_id)."
				AND csm.teamspace_id IS NULL
				AND csm.module_id = mm.module_id
				ORDER BY mm.name";

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
	<col style='width:150px;' >
	<col style='width:250px;' >
	<col style='width:60px;' >
	</colgroup>
	  <tr>
		  <th>Module</th>
		  <th>Description</th>
		  <th>Permissions</th>
	  </tr>
	</table>
	";

	return $c;
}
?>