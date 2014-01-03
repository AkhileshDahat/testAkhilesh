<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."modules/helpdesk/functions/forms/create_ticket.php");

function Edit() {

	GLOBAL $ti;

	$c="";

	$c.=CreateTicket($ti->GetColVal("ticket_id"),$ti->GetColVal("category_id"),$ti->GetColVal("priority_id"),$ti->GetColVal("location_id"),$ti->GetColVal("location_name"),$ti->GetColVal("status_id"),$ti->GetColVal("significance_id"),$ti->GetColVal("status_name"),
									 $ti->GetColVal("user_id_logging"),$ti->GetColVal("user_id_logging_name"),$ti->GetColVal("user_id_problem"),$ti->GetColVal("user_id_problem_name"),
									 $ti->GetColVal("title"),$ti->GetColVal("description"),$ti->GetColVal("technical_description"),$ti->GetColVal("solution"),$ti->GetColVal("technical_solution"),
									 $ti->GetColVal("user_problem_name"),$ti->GetColVal("user_problem_contact_tel_no"),$ti->GetColVal("user_problem_contact_email"),
									 $ti->GetColVal("date_due"),$ti->GetColVal("date_estimated_completion"),$ti->GetColVal("date_start_work"),
									 $ti->GetColVal("estimate_time_spent"),$ti->GetColVal("actual_time_spent"),
									 "index.php?module=helpdesk&task=ticket_details&jshow=edit&ticket_id=".$_GET['ticket_id']);
	return $c;
}
?>