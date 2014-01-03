<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/forms/create_fields.php";

function OrderForm($batch_id="",$category_name="",$category_id="",$sub_category_name="",$sub_category_id="",$quantity="",$cost_price="") {
	$show_form=True;
	$c="";

	$c.="<SCRIPT LANGUAGE='JavaScript'><!--\n";
	$c.="function getSubCatUrl() {\n";
	    $c.="var cat_id;\n";
	    $c.="if (document.order_form.category_id.value == '') {\n";
	    	$c.="alert('Please choose a category first');\n";
	    $c.="}\n";
	    $c.="else {\n";
	    	$c.="cat_id = document.order_form.category_id.value;\n";
	    	//$c.="cat_id = 22;\n";
	    	//$c.="alert('Cat ID='+cat_id);\n";
	    	$c.="window.open('modules/sstars/bin/new_window/select_sub_category.php?fn=order_form&category_id='+cat_id,'Sub Category','scrollbars=no,status=no,width=400,height=350');\n";
	    $c.="}\n";
	$c.="}\n";
	$c.="//--></SCRIPT>\n";

	/*
		DESIGN THE FORM
	*/
	$c.="<table class='plain_border_form' cellspacing='0' cellpadding='5'>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='4'><img src='images/nuvola/32x32/apps/korganizer_todo.png'>Batch Items</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2'>Category*</td>\n";
			$c.="<td colspan='2'>Sub Category*</td>\n";
			$c.="<td>Quantity*</td>\n";
			$c.="<td></td>\n";
		$c.="</tr>\n";

		$c.="<form method='post' name='order_form' action='index.php?module=sstars&task=order' onsubmit='return ValidateForm(this);'>\n";
		$c.="<tr>\n";
			$c.="<td>".DrawInput("category_name", "input_reqd", $category_name, "20", "255", "readonly")."</td>\n";
			$c.="<td><img src='images/nuvola/16x16/actions/viewmag.png' OnClick=\"window.open('modules/sstars/bin/new_window/select_category.php?fn=order_form','Category','scrollbars=no,status=no,width=400,height=350')\"></td>\n";
			$c.="<input type='hidden' name='category_id' value='".$category_id."'>\n";
			$c.="<td>".DrawInput("sub_category_name", "input_reqd", $sub_category_name, "20", "255", "readonly")."</td>\n";
			$c.="<td><img src='images/nuvola/16x16/actions/viewmag.png' onClick=\"getSubCatUrl()\"></td>\n";
			$c.="<input type='hidden' name='sub_category_id' value='".$sub_category_id."'>\n";
			$c.="<td>".DrawInput("quantity","input_reqd",$quantity,"10","255")."</td>\n";
			$c.="<td valign='top' class='head' colspan='4'>".DrawSubmit("submit","Save Item","buttonstyle","FormSubmit","")."</td>\n";
		$c.="</tr>\n";
	$c.="</form>\n";
	$c.="</table>\n";
	return $c;
}
?>