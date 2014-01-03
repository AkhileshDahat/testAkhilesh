<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function AddVisitor($category_id,$remarks,$date_expected,$visitor_full_name,$visitor_identification_number,$visitor_contact_number,
										$total_guests,$vehicle_registration_number) {

	/* INCLUDE THE JCALENDAR */
	$GLOBALS['head']->IncludeFile("jscalendar");

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("apply");
	$fv->OpenTag();
	$fv->ValidateFields("account_name");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=visitors&task=add_visitor","post","apply","onSubmit=\"ValidateForm(this)\"");
	$form->Header("../modules/visitors/images/default/add_visitor.png","Add visitor");

	$form->BreakCell("Required Data");
	$form->Input("Vehicle Registration","vehicle_registration_number","","","",$vehicle_registration_number);
	$form->InformationCell("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;or&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",False);
	$form->Input("Identification number","visitor_identification_number","","","",$visitor_identification_number);
	$form->Date("Expected Date","date_expected",$date_expected);
	$form->ShowDropDown("Category","category_id","category_name","visitor_category_master","category_id",$category_id,"","WHERE workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'],"","input_reqd");

	$form->BreakCell("Visitor Personal Information");
	$form->Input("Full name","visitor_full_name","","","",$visitor_full_name);
	$form->Input("Contact number","visitor_contact_number","","","",$visitor_contact_number);
	$form->Input("Total guests","total_guests","","","",$total_guests);

	$form->BreakCell("Other information");
	$form->Textarea("Remarks","remarks",5,30,$remarks);


	//$form->SetFocus("period_from");

	$c.=$form->DrawForm();

	return $c;
}
?>