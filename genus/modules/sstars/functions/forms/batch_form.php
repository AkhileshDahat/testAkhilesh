<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function BatchForm($invoice_number="",$description="",$cost_price="",$date_purchase="",$date_delivery="",$vendor_id="",$vendor_name="") {

	/* INCLUDE THE JCALENDAR */
	$GLOBALS['head']->IncludeFile("jscalendar");

	$show_form=True;
	$c="";

	/*
		FIELD VALIDATION
	*/
	//$fv=new FieldValidation;
	//$fv->OpenTag();
	//$fv->ValidateFields("invoice_number,cost_price,date_delivery,date_purchase,vendor_name,vendor_id");
	//$fv->CloseTag();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=sstars&task=batch","post","categories","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Batch Entry");

	$form->Input("Invoice Number*","invoice_number","","","",$invoice_number);
	$form->Textarea("Description","description",5,30,$description);
	$form->Input("Cost Price*","cost_price","","","",$cost_price,"8");
	$form->Date("Purchase Date*","date_purchase",$date_purchase,$date_purchase);
	$form->Date("Delivery Date*","date_delivery",$date_delivery,$date_delivery);
	//$form->Input("Vendor*","vendor_name","","","",$vendor_name);

	$form->SetFocus("invoice_number");

	$c.=$form->DrawForm();

	return $c;
}
?>