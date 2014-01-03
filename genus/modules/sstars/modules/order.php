<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/sstars/classes/order_id.php";
require_once $GLOBALS['dr']."modules/sstars/functions/forms/order_form.php";
require_once $GLOBALS['dr']."modules/sstars/functions/browse/browse_order_details.php";
require_once $GLOBALS['dr']."classes/design/tab_boxes.php";

function LoadTask() {

	$c="";

	/*
	**************************************************************************
		PERFORM THE ACTIONS HERE BEFORE WE START DISPLAYING STUFF
	***************************************************************************
	*/
	/* CONFIRM ORDER */
	if (ISSET($_GET['subtask']) && ISSET($_SESSION['order_id'])) {
		unset($_SESSION['order_id']);
	}
	/* ADD */
	if (ISSET($_POST['FormSubmit'])) {
		$obj_oi=new OrderID;
		//echo "Adding";
		if (!ISSET($_SESSION['order_id'])) {
			$obj_oi->AddMaster();
			$order_id=$obj_oi->GetInfo("order_id");
			echo $order_id."<br>";
			session_register("order_id");
			$_SESSION['order_id']=$order_id;
		}
		/* ADD THE DETAILS */
		$result_add=$obj_oi->AddDetails($_SESSION['order_id'],$_POST['category_id'],$_POST['sub_category_id'],$_POST['quantity']);


		/* SEE THAT IT WAS A SUCCESS */
		if (!$result_add) {
			$c.="Failed";
			$c.=$obj_ci->ShowErrors();
		}
		else {
			$c.="Success";
		}
	}


	/* LAYOUT OF GUI */
	//$tab_array=array("browse","add","history");
	$c.=OrderForm();
	if (ISSET($_SESSION['order_id'])) {
		$c.=BrowseOrderDetails($_SESSION['order_id']);
		$c.="<br><input type='button' onClick=\"document.location.href='index.php?module=sstars&task=order&subtask=confirm'\" value='Confirm Order' class='buttonstyle'>\n";
	}

	return $c;
}
?>