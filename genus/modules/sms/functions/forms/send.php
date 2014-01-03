<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function Send($message="") {

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
	$form->SetCredentials("index.php?module=sms&task=send","post","send_sms","onSubmit=\"ValidateForm(this)\"");
	$form->Header("../modules/sms/images/default/send_sms.png","Send SMS");

	//$form->Date("Date From","date_from",$date_from);
	//$form->Checkbox("Half day","date_from_half_day",$date_from_half_day);
	//$form->Radio("am/pm","date_from_half_day_am_pm",array("am","pm"),$date_from_half_day_am_pm);

	//$form->Date("Date To","date_to",$date_to,$date_to_half_day);
	//$form->Checkbox("Half day","date_to_half_day",$date_to_half_day);
	//$form->Radio("am/pm","date_to_half_day_am_pm",array("am","pm"),$date_to_half_day_am_pm);

	//$form->ShowDropDown("Category","category_id","category_name","leave_category_master","category_id",$category_id,"","WHERE workspace_id = ".$GLOBALS['workspace_id'],"","input_reqd");	
	$form->BreakCell("Compose your message");
	$form->Textarea("Message","message",5,30,$message);
	$form->BreakCell("Choose your recipients");
	$form->Checkbox("All Users","send_all_users");
	//$form->Checkbox("Users in this workspace/teamspace","users_teamspace");
	//$form->Checkbox("CRM users","users_crm");	
	//$form->SetFocus("period_from");

	$c.=$form->DrawForm();

	return $c;
}
?>