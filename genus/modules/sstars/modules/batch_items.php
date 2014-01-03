<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/sstars/functions/forms/batch_items.php";
require_once $GLOBALS['dr']."modules/sstars/functions/browse/browse_batch_items.php";
require_once $GLOBALS['dr']."modules/sstars/classes/item_id.php";

function LoadTask() {
	$c="";
	/*
		THE FORM IS BEING SUBMITTED
	*/
	if (ISSET($_POST['new_batch'])) {

		$err=False; /* ASSUME NO ERRORS AS WE GO ALONG */
		$c.="Saving...<br>";



		$obj_ii=new ItemID; /* OBJECT TO CREATE A NEW ITEM */
		$obj_ii->GetFormPostedValues();
		$result=$obj_ii->Add();
		if ($result) {
			$c.="Success!<br>\n";
		}
		else {
			$c.=$obj_ii->ShowErrors();
		}
	}

	if (ISSET($_GET['batch_id'])) {
		$c.=BatchItems($_GET['batch_id']);
		$c.=BrowseBatchItems($_GET['batch_id']);
	}
	else {
		$c.="Invalid, please choose a batch first";
	}

	return $c;
}
?>