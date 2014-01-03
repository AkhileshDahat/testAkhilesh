<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/documents/classes/document_id.php";
require_once $GLOBALS['dr']."modules/documents/functions/browse/pending_approval.php";

function LoadTask() {
	$c="";
	/* PROCESS THE ACTIONS */
	if (ISSET($_GET['document_id']) & ISSET($_GET['approve'])) {
		$di=new DocumentID;
		$di->SetParameters($_GET['document_id']);
		$result=$di->ApproveRejectDocument($_GET['approve']);
		if ($result) {
			$c.="Success";
		}
		else {
			$c.="Failed";
		}
	}
	/* INCLUDE THE BROWSE FUNCTION */
	$c.=PendingApproval();

	return $c;
}
?>