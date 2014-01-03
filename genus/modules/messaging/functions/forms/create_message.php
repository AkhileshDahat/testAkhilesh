<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."include/functions/forms/create_fields.php";
require_once $dr."include/functions/db/get_col_value.php";
require_once $dr."include/functions/forms/dynamic_dropdown.php";
require_once $dr."classes/form/form_validate.php";
require_once $dr."include/functions/misc/jcalendar.php";

function CreateMessage() {
	$db=$GLOBALS['db'];
	$ui=$GLOBALS['ui'];
	$show_form=True;
	/*
		THE FORM IS BEING SUBMITTED
	*/
	echo "Sending Message...<br>\n";
	if (ISSET($_POST['message'])) {

		print_r($_POST['group_id']);
	}

	/*
		FIELD VALIDATION
	*/
	$fv=new FieldValidation;
	$fv->OpenTag();
	$fv->ValidateFields("invoice_number,cost_price,delivery_date,purchase_date,vendor_name,vendor_id");
	$fv->CloseTag();

	$c="<script type='text/javascript'><!--\n";
	$c.="function entertag(evt){\n";
	$c.="evt=(evt)?evt:event;\n";
	$c.="charCode=(evt.which)?evt.which:evt.keyCode;\n";
	$c.="if(charCode==13)document.create_message.submit();\n";
	$c.="}\n";
	$c.="window.onload=attach;\n";
	$c.="function attach(){\n";
		$c.="document.create_message.message.onkeypress = entertag;\n";
	$c.="}\n";
	$c.="//--></script>\n";

	/*
		DESIGN THE FORM
	*/
	//if (!$show_form) { return $c; }
	$c.="<table class='plain_border' cellspacing='0' cellpadding='5' width='500'>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='4'><img src='images/crystalclear/32x32/apps/web.png'>Send Message</td>\n";
		$c.="</tr>\n";
		$c.="<form method='post' name='create_message' action='index.php?module=messaging&task=create_message' onsubmit='return ValidateForm(this);'>\n";
		$c.="<tr>\n";
			$c.="<td valign='top'>Message</td>\n";
			$c.="<td>".DrawTextarea("message","inputbox","","5","40")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='head' colspan='4'>".DrawSubmit("submit","Send Message","buttonstyle","add_project","")."&nbsp;".DrawSubmit("reset",_RESET_FORM_,"buttonstyle","reset")."&nbsp;</td>\n";
		$c.="</tr>\n";
	$c.="</form>\n";
	$c.="</table>\n";
	/* FOCUS ON THE INVOICE NUMBER FIELD */
	$c.="<script language='JavaScript'>\n";
	$c.="document.create_message.message.focus();\n";
	$c.="</script>\n";
	return $c;
}
?>