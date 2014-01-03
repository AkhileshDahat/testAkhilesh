<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."classes/design/tab_boxes.php";
require_once $GLOBALS['dr']."modules/helpdesk/classes/ticket_id.php";

function LoadTask() {

	$c="";
	GLOBAL $ti;

	/* EDITING THE TICKET MUST COME FIRST */
	if (ISSET($_POST['FormSubmit']) && IS_NUMERIC($_GET['ticket_id'])) {
		//global $ti;
		$ti=new TicketID();
		$result=$ti->SetParameters($_GET['ticket_id']);
		$result_edit=$ti->Edit($_POST['category_id'],$_POST['priority_id'],$_POST['location_id'],$_POST['status_id'],$_POST['significance_id'],
						 $_POST['user_id_problem'],
						 $_POST['user_problem_name'],$_POST['user_problem_contact_tel_no'],$_POST['user_problem_contact_email'],
						 $_POST['title'],$_POST['description'],$_POST['technical_description'],$_POST['solution'],$_POST['technical_solution'],
						 $_POST['date_due'],$_POST['date_estimated_completion'],$_POST['date_start_work']);
		if ($result_edit) {
			$c.="Success";
		}
		else {
			$c.="No changes recorded to ticket";
			$c.=$ti->ShowErrors();
		}
	}

	$ti=new TicketID();
	$result=$ti->SetParameters($_GET['ticket_id']);
	if (!$result) { return "Access Denied"; }

	/* LOOK FOR LOCKED TICKETS BEING EDITED */
	if ($ti->TicketLockedForCurrentUser()) {
	 	return "Ticket Locked by someone else. Wait for the ticket to be unlocked or press refresh";
	}
	else {
	 	//echo "not locked";
		$ti->LockTicket();
	}

	$tab_array=array("summary","edit","delegation","attachments","tags","history");
	$tb=new TabBoxes;
	$c.=$tb->DrawBoxes($tab_array,$GLOBALS['dr']."modules/helpdesk/modules/ticket_details/");

	if (ISSET($_GET['jshow'])) {
		$c.=$tb->BlockShow($_GET['jshow']);
	}

	return $c;
}

?>