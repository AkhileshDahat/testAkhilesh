<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function CreateTicket($ticket_id="",$category_id="",$priority_id="",$location_id="",$location_name="",$status_id="",$significance_id="",$status_name="",
											$user_id_logging="",$user_id_logging_name="",$user_id_problem="",$user_id_problem_name="",
											$title="",$description="",$technical_description="",$solution="",$technical_solution="",
											$user_problem_name="",$user_problem_contact_tel_no="",$user_problem_contact_email="",
											$date_due="",$date_start_work="",$date_estimated_completion="",
											$estimate_time_spent="",$actual_time_spent="",
											$form_action="index.php?module=helpdesk&task=create_ticket") {
	$ui=$GLOBALS['ui'];
	//$ms=$GLOBALS['ms'];

	/* INCLUDE THE JCALENDAR */
	$GLOBALS['head']->IncludeFile("jscalendar");

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("create_ticket");
	$fv->OpenTag();
	$fv->ValidateFields("account_name");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials($form_action,"post","create_ticket","");
	$form->Header("crystalclear/48x48/apps/access.png",_HELPDESK_CREATE_TICKET_FORM_TITLE_);

	//$form->Hidden("module_id",$module_id);
	$form->BreakCell("General Information");

	$form->Input(_HELPDESK_TICKET_TITLE_,"title","","","",$title,"50","input_reqd");
	$form->Textarea(_HELPDESK_TICKET_DESCRIPTION_,"description",15,50,$description);

	/* ITEMS ONLY SET FOR EXISTING TICKETS */
	if (!EMPTY($ticket_id)) {
		$form->Textarea(_HELPDESK_TICKET_TECHNICAL_DESCRIPTION_,"technical_description",15,50,$technical_description);
		$form->Textarea(_HELPDESK_TICKET_SOLUTION_,"solution",15,50,$solution);
		$form->Textarea(_HELPDESK_TICKET_TECHNICAL_SOLUTION_,"technical_solution",15,50,$technical_solution);
	}

	//$form->ShowDropDown(_HELPDESK_TICKET_STATUS_,"category_id","category_name","helpdesk_category_master","category_id",$category_id,"","WHERE workspace_id = '".$ui->WorkspaceID()."' AND teamspace_id ".$GLOBALS['teamspace_sql']);
	$form->ShowDropDown(_HELPDESK_TICKET_CATEGORY_,"category_id","category_name","helpdesk_category_master","category_id",$category_id,"","WHERE workspace_id = '".$ui->WorkspaceID()."' AND teamspace_id ".$GLOBALS['teamspace_sql']);
	$form->ShowDropDown(_HELPDESK_TICKET_PRIORITY_,"priority_id","priority_name","helpdesk_priority_master","priority_id",$priority_id,"","WHERE workspace_id = '".$ui->WorkspaceID()."' AND teamspace_id ".$GLOBALS['teamspace_sql']);

	//$form->ShowDropDown(_HELPDESK_TICKET_LOCATION_,"location_id","location_name","hrms_location_master","location_id",$location_id,"","WHERE workspace_id = '".$ui->WorkspaceID()."'");

	/* LOCATION */
	$form->InputPopup(_HELPDESK_TICKET_LOCATION_,"location_name",$location_name,"hrms_location_master","create_ticket","location_name","location_id");
	$form->Hidden("location_id",$location_id);

	/* STATUS CAN ONLY BE SET FOR EXISTING TICKETS */
	if (!EMPTY($ticket_id)) {
		$form->BreakCell("Ticket Status");
		$form->ShowDropDown(_HELPDESK_TICKET_STATUS_,"status_id","status_name","helpdesk_status_master","status_id",$status_id,"","WHERE workspace_id = '".$ui->WorkspaceID()."' AND teamspace_id ".$GLOBALS['teamspace_sql']);
		$form->ShowDropDown(_HELPDESK_TICKET_SIGNIFICANCE_,"significance_id","significance_name","helpdesk_significance_master","significance_id",$significance_id,"","WHERE workspace_id = '".$ui->WorkspaceID()."' AND teamspace_id ".$GLOBALS['teamspace_sql']);
	}

	/* INTERNAL USER REQUESTING HELP */
	$form->BreakCell("Internal User Requiring Assistance");
	$form->InputPopup(_HELPDESK_TICKET_USER_PROBLEM_,"user_id_problem_name",$user_id_problem_name,"hrms_user_master","create_ticket","user_id_problem_name","user_id_problem");
	$form->Hidden("user_id_problem",$user_id_problem);

	/* EXTERNAL USER REQUESTING HELP */
	$form->BreakCell("External User Requiring Assistance");
	$form->Input("Name","user_problem_name","","","",$user_problem_name,"20","input");
	$form->Input("Tel no","user_problem_contact_tel_no","","","",$user_problem_contact_tel_no,"20","input");
	$form->Input("Email","user_problem_contact_email","","","",$user_problem_contact_email,"20","input");

	$form->BreakCell("Date Information");
	$form->DateTime("Due date","date_due",$date_due);

	/* ITEMS ONLY SET FOR EXISTING TICKETS */
	if (!EMPTY($ticket_id)) {
		$form->DateTime("Start work","date_start_work",$date_start_work);
		$form->DateTime("Estimated completion","date_estimated_completion",$date_estimated_completion);
	}

	$form->SetFocus("title");
	$form->CloseForm();

	$c.=$form->DrawForm();

	return $c;
}
?>