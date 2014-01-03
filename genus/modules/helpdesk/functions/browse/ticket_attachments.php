<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function TicketAttachments($ticket_id) {

	$c="";

	$sr=new ShowResults;
	$sr->SetParameters(True);

	$sr->DrawFriendlyColHead(array("","Name","Size","Type","")); /* COLS */
	$sr->Columns(array("attachment_id","filename","filesize","filetype","download"));
	$sr->Query("SELECT hta.attachment_id,hta.filename,hta.filesize,hta.filetype,'download' as download
							FROM ".$GLOBALS['database_prefix']."helpdesk_ticket_attachments hta
							WHERE hta.ticket_id = ".EscapeData($ticket_id)."
							ORDER BY hta.attachment_id DESC
							");
	for ($i=0;$i<$sr->CountRows();$i++) {
		$attachment_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$name=$sr->GetRowVal($i,1); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */

		/* THIS IS THE LINK TO DETAILS */
		$sr->ModifyData($i,4,"<a href='modules/helpdesk/bin/download_document.php?attachment_id=".$attachment_id."' title='Click to go'>Download</a>");

		if (STRLEN($name) > 20) { $v_filename=SUBSTR($name,0,20)."..."; } else { $v_filename=$name; }
		$sr->ModifyData($i,1,$v_filename);
	}
	$sr->RemoveColumn(0);

	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Browse Ticket Attachments");
	$sr->Footer();
	$c.=$sr->Draw();

	return $c;
}
?>