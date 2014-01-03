<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function Add() {

	/* INCLUDE THE JCALENDAR */
	//$GLOBALS['head']->IncludeFile("jscalendar");

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("send_sms");
	$fv->OpenTag();
	$fv->ValidateFields("account_name");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=adminsms&task=add","post","send_sms","onSubmit=\"ValidateForm(this)\"");
	$form->Header("../modules/sms/images/default/send_sms.png","SMS Transaction Balances");

	//$form->Date("Date From","date_from",$date_from);
	//$form->Checkbox("Half day","date_from_half_day",$date_from_half_day);
	//$form->Radio("am/pm","date_from_half_day_am_pm",array("am","pm"),$date_from_half_day_am_pm);

	//$form->Date("Date To","date_to",$date_to,$date_to_half_day);
	//$form->Checkbox("Half day","date_to_half_day",$date_to_half_day);
	//$form->Radio("am/pm","date_to_half_day_am_pm",array("am","pm"),$date_to_half_day_am_pm);
	$form->BreakCell("Select the workspace & teamspace");
	$form->ShowDropDown("Workspace","workspace_id","workspace_code","core_workspace_master","workspace_id","","","","","input_reqd");	
	$form->ShowDropDown("Teamspace","teamspace_id","name","core_teamspace_master","teamspace_id","","","","","input_reqd");	
	$form->BreakCell("Enter the description");
	$form->Textarea("Transaction Description","transaction_description",5,30,"");
	$form->BreakCell("Enter the amount");
	$form->Input("Balance","account_balance_change","","","","",5);
	
	//$form->Checkbox("Users in this workspace/teamspace","users_teamspace");
	//$form->Checkbox("CRM users","users_crm");	
	//$form->SetFocus("period_from");

	$c.=$form->DrawForm();

	return $c;
}
?>