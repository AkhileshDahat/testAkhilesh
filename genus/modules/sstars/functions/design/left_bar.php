<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."include/functions/forms/create_fields.php";

function LeftBar() {
	$c.="<table width='100%' class='plain_border'>\n";
		$c.="<tr>\n";
			$c.="<td class='colhead' colspan='2'>Quick Menu</td>\n";
		$c.="</tr>\n";
		$c.="<form method='get' action='index.php'>\n";
		$c.="<input type='hidden' name='module' value='sstars'>\n";
		$c.="<input type='hidden' name='task' value='orders'>\n";
		$c.="<tr>\n";
			$c.="<td>Order</td>\n";
			$c.="<td>".DrawInput("order_id","input_reqd",$_GET['order_id'],"7","255","")."</td>\n";
		$c.="</tr>\n";
		$c.="</form>\n";

	$c.="</table>\n";

	return $c;
}

?>