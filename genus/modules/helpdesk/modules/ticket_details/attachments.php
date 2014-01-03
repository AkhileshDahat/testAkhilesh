<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/rad_upload/upload_applet.php";
require_once $GLOBALS['dr']."modules/helpdesk/functions/browse/ticket_attachments.php";

function Attachments() {

	$c="";
	$c.="<table class='plain_border'>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2' class='colhead'>Ticket Attachments <a href='index.php?module=helpdesk&task=ticket_details&ticket_id=".EscapeData($_GET['ticket_id'])."&jshow=attachments'>Refresh</a></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td width='60%' valign='top'>\n";
			$c.=TicketAttachments($_GET['ticket_id']);
			$c.="</td>\n";
			$c.="<td width='40%' valign='top'>\n";
			$c.=UploadApplet($GLOBALS['wb']."modules/helpdesk/bin/ticket_attachments.php?ticket_id=".EscapeData($_GET['ticket_id'])."&PHPSESSID=".session_id());
			$c.="</td>\n";
		$c.="</tr>\n";
	$c.="</table>\n";

	return $c;
}
?>