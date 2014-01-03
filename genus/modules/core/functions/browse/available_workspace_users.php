<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function AvailableWorkspaceUsers($workspace_id) {

	$sql="SELECT cum.full_name,
					IF(csu.user_id IS NULL,
						concat('<a href=index.php?module=core&task=available_workspace_users&subtask=install&workspace_id=$workspace_id&user_id=',cum.user_id,'>INSTALL</a>'),
						concat('<a href=index.php?module=core&task=available_workspace_users&subtask=remove&workspace_id=$workspace_id&user_id=',cum.user_id,'>REMOVE</a>'))
				FROM ".$GLOBALS['database_prefix']."core_user_master cum
				LEFT JOIN ".$GLOBALS['database_prefix']."core_space_users csu
				ON cum.user_id = csu.user_id
				AND csu.workspace_id = '".EscapeData($workspace_id)."'
				ORDER BY cum.full_name
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
		  <th>USERNAME</th>
		  <th>STATUS</th>
	  </tr>
	</table>
	";

	return $c;
}
?>