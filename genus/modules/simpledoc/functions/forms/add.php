<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function Add($message="") {

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
	$form->SetCredentials("index.php?module=simpledoc&task=add","post","send_sms","onSubmit=\"ValidateForm(this)\" enctype=\"multipart/form-data\" ");
	$form->Header("../modules/sms/images/default/send_sms.png","Add a new document");

	//$form->Date("Date From","date_from",$date_from);
	//$form->Checkbox("Half day","date_from_half_day",$date_from_half_day);
	//$form->Radio("am/pm","date_from_half_day_am_pm",array("am","pm"),$date_from_half_day_am_pm);

	//$form->Date("Date To","date_to",$date_to,$date_to_half_day);
	//$form->Checkbox("Half day","date_to_half_day",$date_to_half_day);
	//$form->Radio("am/pm","date_to_half_day_am_pm",array("am","pm"),$date_to_half_day_am_pm);

	//$form->ShowDropDown("Category","category_id","category_name","leave_category_master","category_id",$category_id,"","WHERE workspace_id = ".$GLOBALS['workspace_id'],"","input_reqd");
	$form->BreakCell("Select a file on your hard drive");
	//$form->Input("Title","title","","","","","40");
	//$form->Textarea("Message","message",15,60,$message);
	$form->File("attachment");
	//$form->Checkbox("Users in this workspace/teamspace","users_teamspace");
	//$form->Checkbox("CRM users","users_crm");
	//$form->SetFocus("period_from");

	$c.=$form->DrawForm();

	return $c;
}
?>