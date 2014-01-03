<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/helpdesk/classes/ticket_id.php";
require_once $GLOBALS['dr']."modules/helpdesk/functions/forms/create_ticket.php";

function LoadTask() {

	$c="";

	/* PROCESSING */
	if (ISSET($_POST['FormSubmit'])) {
		global $ti;
		$ti=new TicketID();
		$insert_result=$ti->Add($_POST['category_id'],$_POST['priority_id'],$_POST['location_id'],
						 $_POST['user_problem_name'],$_POST['user_problem_contact_tel_no'],$_POST['user_problem_contact_email'],
						 $_POST['title'],$_POST['description'],
						 $_POST['date_due']);

		if (!$insert_result) {
			$c.="Failed to insert";
			$c.=$ti->ShowErrorS();
		}
		else {
			$c.="Success!";
		}
	}

	/* FORM CREATION FUNCTION */
	$c.=CreateTicket();
	return $c;
}

?>