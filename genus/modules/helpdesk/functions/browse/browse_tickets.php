<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");
require_once($GLOBALS['dr']."modules/helpdesk/classes/category_id.php");

function BrowseTickets($p_filter="",$show_edit=true,$show_records=true) {

	$c="";

	$sql_table_joins="";

	/* FILTER VARIOUS VIEWS OF ALL THE TICKETS */
	if ($p_filter=="my") { $sql_extra="AND ht.user_id_logging = ".$_SESSION['user_id']; }
	elseif ($p_filter=="delegated") { $sql_table_joins=", ".$GLOBALS['database_prefix']."helpdesk_ticket_delegation htd"; $sql_extra="AND ht.ticket_id = htd.ticket_id AND htd.user_id = ".$_SESSION['user_id']; }
	else { $sql_extra=""; }

	/* FILTER THE TICKET ID */
	if (ISSET($_POST['ticket_id']) && IS_NUMERIC($_POST['ticket_id'])) { $sql_ticket_id="AND ht.ticket_id = ".EscapeData($_POST['ticket_id']); } else { $sql_ticket_id=""; }

	$sql="SELECT concat('<a href=index.php?module=helpdesk&task=ticket_details&ticket_id=',ht.ticket_id,' title=Browse>',ht.ticket_id,'</a>') as f1,
							concat('<a href=index.php?module=helpdesk&task=ticket_details&ticket_id=',ht.ticket_id,' title=Browse>',ht.title,'</a>') as f1,
							ht.date_submit
							FROM ".$GLOBALS['database_prefix']."helpdesk_tickets ht
							$sql_table_joins
							WHERE workspace_id = ".$GLOBALS['workspace_id']."
							AND teamspace_id ".$GLOBALS['teamspace_sql']."
							$sql_extra
							$sql_ticket_id
							ORDER BY ht.ticket_id DESC
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
	$c.="<col style='width:200px;' >\n";
	$c.="<col style='width:250px;' >\n";
	$c.="</colgroup>\n";
	  $c.="<tr>\n";
		  $c.="<th>ID</th>\n";
		  $c.="<th>From</th>\n";
		  $c.="<th>Submitted</th>\n";
	  $c.="</tr>\n";
	$c.="</table>\n";

	return $c;
}
?>