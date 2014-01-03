<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."include/functions/forms/create_fields.php";
require_once $dr."include/functions/db/get_col_value.php";
require_once $dr."classes/javascript/form_validate.php";
require_once $dr."include/functions/misc/jcalendar.php";
require_once $dr."modules/sstars/classes/new_batch.php";

function BatchForm() {
	$show_form=True;
	/*
		THE FORM IS BEING SUBMITTED
	*/
	if ($_POST['new_batch']) {
		$err=False; /* ASSUME NO ERRORS AS WE GO ALONG */
		$c.="Saving...<br>";
		$batch_id=EscapeData($_POST['batch_id']);
		$invoice_number=EscapeData($_POST['invoice_number']);
		$description=EscapeData($_POST['description']);
		$cost_price=EscapeData($_POST['cost_price']);
		$purchase_date=EscapeData($_POST['purchase_date']);
		$delivery_date=EscapeData($_POST['delivery_date']);
		$vendor_id=EscapeData($_POST['vendor_id']);
		$vendor_name=EscapeData($_POST['vendor_name']);
		$nb=new NewBatch; /* OBJECT TO CREATE A NEW BATCH */
		/* IF THERE'S NO BATCH ID THEN WE'RE INSERTING A NEW RECORD */
		if (!$_POST['batch_id']) {
			$result=$nb->AddBatch($invoice_number,$description,$cost_price,$purchase_date,$delivery_date,$vendor_id);
			if ($result) {
				$c.="Success!<br>\n";
				$c.="<a href='index.php?module=sstars&task=inventory&sub=batch_items&batch_id=".$nb->BatchID()."'><img src='images/buttons/next.gif' border='0'></a>\n";
				//$show_form=False;
			}
			else {
				$c.=$nb->ShowBatchErrors();
			}
		}
		else {
			$result=$nb->EditBatch($batch_id,$description,$cost_price,$purchase_date,$delivery_date,$vendor_name,$vendor_id);
			if ($result) {
				$c.="Success!<br>\n";
			}
			else {
				$c.=$nb->ShowBatchErrors();
			}
		}
	}

	/*
		FIELD VALIDATION
	*/
	$fv=new FieldValidation;
	$fv->OpenTag();
	$fv->ValidateFields("invoice_number,cost_price,delivery_date,purchase_date,vendor_name,vendor_id");
	$fv->CloseTag();

	/*
		DESIGN THE FORM
	*/
	//if (!$show_form) { return $c; }
	$c.="<table class='plain_border' cellspacing='0' cellpadding='5'>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='4'><img src='images/nuvola/32x32/apps/korganizer_todo.png'>Batch Form</td>\n";
		$c.="</tr>\n";
		$c.="<form method='post' name='batch_form' action='index.php?module=sstars&task=inventory' onsubmit='return ValidateForm(this);'>\n";
		$c.="<input type='hidden' name='batch_id' value=''>\n";
		$c.="<tr>\n";
			$c.="<td>Invoice Number*</td>\n";
			$c.="<td colspan='3'>".DrawInput("invoice_number","input_reqd",$invoice_number,"30","255","onKeyUp=\"frames['invoice_number_lookup'].location.href='modules/sstars/bin/batch/invoice_number_lookup.php?invoice_number='+document.getElementById('invoice_number').value\" autocomplete=off")."<iframe id='invoice_number_lookup' name='invoice_number_lookup' src='modules/sstars/bin/batch/invoice_number_lookup.php' width='100' height='20'scrolling='no' border='0' style='border:none;' frameborder='0'></iframe></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top'>Description</td>\n";
			$c.="<td colspan='3'>".DrawTextarea("description","inputbox",$description,"5","40")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Cost Price*</td>\n";
			$c.="<td colspan='3'>".DrawInput("cost_price","input_reqd",$cost_price,"30","255")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Purchase Date*</td>\n";
			$c.="<td>".DrawInput("purchase_date", "input_reqd", $purchase_date, "20", "255", "readonly");
			$c.=JCalendar("purchase_date","purchase_date_calendar");
			$c.="</td>\n";
			$c.="<td>Delivery Date*</td>\n";
			$c.="<td>".DrawInput("delivery_date", "input_reqd", $delivery_date, "20", "255", "readonly");
			$c.=JCalendar("delivery_date","delivery_date_calendar");
			$c.="</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Vendor*</td>\n";
			$c.="<td colspan='3'>".DrawInput("vendor_name", "input_reqd", $vendor_name, "20", "255", "readonly")."<img src='images/nuvola/16x16/actions/viewmag.png' OnClick=\"window.open('modules/sstars/bin/new_window/select_vendor.php?form_name=batch_form&text=vendor_name&value=vendor_id','Vendor List','scrollbars=no,status=no,width=400,height=350')\"></td>\n";
		$c.="</tr>\n";
		$c.="<input type='hidden' name='vendor_id' id='vendor_id' value='".$vendor_id."'>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='head' colspan='4'>".DrawSubmit("submit","Save Batch","buttonstyle","new_batch",$show_submit)."&nbsp;".DrawSubmit("reset",_RESET_FORM_,"buttonstyle","reset")."&nbsp;</td>\n";
		$c.="</tr>\n";
	$c.="</form>\n";
	$c.="</table>\n";
	/* FOCUS ON THE INVOICE NUMBER FIELD */
	$c.="<script language='JavaScript'>\n";
	$c.="document.batch_form.invoice_number.focus();\n";
	$c.="</script>\n";
	return $c;
}
?>