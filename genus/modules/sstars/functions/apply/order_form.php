<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."include/functions/forms/create_fields.php";
require_once $dr."include/functions/db/get_col_value.php";
require_once $dr."classes/javascript/form_validate.php";
require_once $dr."include/functions/misc/jcalendar.php";
require_once $dr."modules/sstars/classes/new_order_details.php";
require_once $dr."modules/sstars/functions/apply/show_order_details.php";

function OrderForm() {
	$db=$GLOBALS['db'];
	$show_form=True;
	$order_id=$_GET['order_id'];
	/*
		THE FORM IS BEING SUBMITTED
	*/
	if ($_POST['new_order']) {
		if (!$_GET['edit']) {
			$err=False; /* ASSUME NO ERRORS AS WE GO ALONG */
			$c.="Saving...<br>";

			$order_id=EscapeData($_GET['order_id']);
			$category_name=EscapeData($_POST['category_name']);
			$category_id=EscapeData($_POST['category_id']);
			$sub_category_name=EscapeData($_POST['sub_category_name']);
			$sub_category_id=EscapeData($_POST['sub_category_id']);
			$quantity=EscapeData($_POST['quantity']);

			$nod=new NewOrderDetails; /* OBJECT TO CREATE A NEW ITEM */
			$db->start_transaction();
			$result=$nod->AddOrderDetails($order_id,$category_id,$sub_category_id,$quantity,$GLOBALS['user_id']);
			$db->end_transaction();
			if ($result) {
				$c.="Success!<br>\n";
				//$c.="<a href='index.php?module=sstars&task=inventory&sub=order_details&batch_id=".$nb->BatchID()."'><img src='images/buttons/next.gif' border='0'></a>\n";
				unset($category_name);
				unset($category_id);
				unset($sub_category_name);
				unset($sub_category_id);
				unset($quantity);
				$order_id=$nod->OrderID();
			}
			else {
				$c.=$nod->ShowOrderDetailsErrors();
			}
		}
	}

	$c.="<SCRIPT LANGUAGE='JavaScript'><!--\n";
	$c.="function getSubCatUrl() {\n";
	    $c.="var cat_id;\n";
	    $c.="if (document.order_details.category_id.value == '') {\n";
	    	$c.="alert('Please choose a category first');\n";
	    $c.="}\n";
	    $c.="else {\n";
	    	$c.="cat_id = document.order_details.category_id.value;\n";
	    	//$c.="cat_id = 22;\n";
	    	//$c.="alert('Cat ID='+cat_id);\n";
	    	$c.="window.open('modules/sstars/bin/new_window/select_sub_category.php?fn=order_details&category_id='+cat_id,'Sub Category','scrollbars=no,status=no,width=400,height=350');\n";
	    $c.="}\n";
	$c.="}\n";
	$c.="//--></SCRIPT>\n";

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
	$c.="<table class='plain' cellspacing='0' cellpadding='5'>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='4'><img src='images/nuvola/32x32/apps/kwrite.png'>Order form</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Category*</td>\n";
			$c.="<td>Sub Category*</td>\n";
			$c.="<td>Quantity*</td>\n";
			$c.="<td></td>\n";
		$c.="</tr>\n";

		$c.="<form method='post' name='order_details' action='index.php?module=sstars&task=apply&order_id=".$order_id."' onsubmit='return ValidateForm(this);'>\n";
		$c.="<tr>\n";
			$c.="<td>".DrawInput("category_name", "input_reqd", $category_name, "20", "255", "readonly")."<img src='images/nuvola/16x16/actions/viewmag.png' OnClick=\"window.open('modules/sstars/bin/new_window/select_category.php?fn=order_details','Category','scrollbars=no,status=no,width=400,height=350')\"></td>\n";
			$c.="<td>".DrawInput("sub_category_name", "input_reqd", $sub_category_name, "20", "255", "readonly")."<img src='images/nuvola/16x16/actions/viewmag.png' onClick=\"getSubCatUrl()\"></td>\n";
			$c.="<input type='hidden' name='category_id' value='".$category_id."'>\n";
			$c.="<input type='hidden' name='sub_category_id' value='".$sub_category_id."'>\n";
			$c.="<td>".DrawInput("quantity","input_reqd",$quantity,"10","255")."</td>\n";
			$c.="<td valign='top' class='head' colspan='4'>".DrawSubmit("submit","Save Item","buttonstyle","new_order",$show_submit)."&nbsp;".DrawSubmit("reset",_RESET_FORM_,"buttonstyle","reset")."&nbsp;</td>\n";
		$c.="</tr>\n";
	$c.="</form>\n";
	$c.="</table>\n";

	$c.=ShowOrderDetails(EscapeData($order_id));
	return $c;
}
?>