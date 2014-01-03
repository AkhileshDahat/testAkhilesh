<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr'].'include/functions/forms/transfer_select.php';

function TicketTags($ticket_id) {

	/* DATABASE CONNECTION */
	$db=$GLOBALS['db'];
	//$app_db=$GLOBALS['app_db'];

	/* FIRST SQL IS TO DISPLAY ALL ROLES NOT USED IN THIS CATEGORY */
	$sql="SELECT um.tag_id, um.tag_name
				FROM ".$GLOBALS['database_prefix']."helpdesk_tag_master um
				WHERE um.tag_id NOT IN (
					SELECT tag_id
					FROM ".$GLOBALS['database_prefix']."helpdesk_ticket_tags
					WHERE ticket_id = ".$ticket_id."
				)
				AND um.workspace_id = ".$GLOBALS['workspace_id']."
				AND um.teamspace_id ".$GLOBALS['teamspace_sql']."
				ORDER BY um.tag_name";
	$sql1="SELECT htt.tag_id, um.tag_name
				FROM ".$GLOBALS['database_prefix']."helpdesk_ticket_tags htt, ".$GLOBALS['database_prefix']."helpdesk_tag_master um
				WHERE htt.tag_id = um.tag_id
				AND um.workspace_id = ".$GLOBALS['workspace_id']."
				AND um.teamspace_id ".$GLOBALS['teamspace_sql']."
				AND htt.tag_id IN (
					SELECT tag_id
					FROM ".$GLOBALS['database_prefix']."helpdesk_ticket_tags
					WHERE ticket_id = ".$ticket_id."
				)
				ORDER BY um.tag_name";
	//echo $sql1;
	$s="<table class='summary' cellpadding=0>\n";
	$s.="<form method='post' action='index.php?module=helpdesk&task=ticket_details&ticket_id=".EscapeData($_GET['ticket_id'])."&jshow=tags'>\n";
		$s.="<tr>\n";
			$s.="<td>\n";
			$s.="<table>\n";
				$s.="<tr>\n";
					$s.="<td width='48'><img src='images/nuvola/22x22/actions/encrypted.png'></td>\n";
					$s.="<td class='head'>Ticket Tags</td>\n";
				$s.="</tr>\n";
			$s.="</table>\n";
			$s.="</td>\n";
		$s.="</tr>\n";
			$s.="<tr class='font12'>\n";
				$s.="<td>\n";
				$s.=TransferSelect("tag_id", "tag_name", $sql, $sql1, "Select to add", "Select to remove",10);
				$s.="</td>\n";
		$s.="</tr>\n";
		$s.="<tr>\n";
			$s.="<td>";
			$s.="<input type='submit' name='submit' value='Apply Changes' class='buttonstyle'></td>\n";
			$s.="</td>";
		$s.="</form>\n";
	$s.="</tr>\n";

	$s.="</table>\n";
	return $s;
}

?>