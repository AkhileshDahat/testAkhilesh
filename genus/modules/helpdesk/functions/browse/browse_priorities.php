<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function BrowsePriorities() {

	$sql="SELECT priority_id,priority_name,
							concat('<a href=index.php?module=helpdesk&task=priorities&subtask=edit&priority_id=',priority_id,' title=Browse>Edit</a>') as edit,
							concat('<a href=index.php?module=helpdesk&task=priorities&subtask=delete&priority_id=',priority_id,' title=Browse>Delete</a>') as del
							FROM ".$GLOBALS['database_prefix']."helpdesk_priority_master
							WHERE workspace_id = ".$GLOBALS['workspace_id']."
							AND teamspace_id ".$GLOBALS['teamspace_sql']."
							ORDER BY priority_name
							";

	$sql = str_replace("\n","",$sql);
	//$sql = str_replace("  ","",$sql);
	$sql = str_replace("\t","",$sql);
	//$sql = str_replace(Chr(13),"",$sql);
	//echo $sql;
	$_SESSION['ex2'] = $sql;

	$head = $GLOBALS['head'];
	$head->IncludeFile("rico2rc2");

	$c="";

	$c.="<p class='ricoBookmark'><span id='ex2_timer' class='ricoSessionTimer'></span><span id='ex2_bookmark'>&nbsp;</span></p>\n";

	$c.="<table id='ex2' class='ricoLiveGrid' cellspacing='0' cellpadding='0'>\n";
	$c.="<colgroup>\n";
	$c.="<col style='width:40px;' >\n";
	$c.="<col style='width:350px;' >\n";
	$c.="<col style='width:50px;' >\n";
	$c.="<col style='width:50px;' >\n";
	$c.="</colgroup>\n";
	  $c.="<tr>\n";
		  $c.="<th>ID</th>\n";
		  $c.="<th>Name</th>\n";
		  $c.="<th>Edit</th>\n";
		  $c.="<th>Delete</th>\n";
	  $c.="</tr>\n";
	$c.="</table>\n";


	return $c;
}
?>