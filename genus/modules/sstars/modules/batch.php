<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/sstars/classes/batch_id.php";
require_once $GLOBALS['dr']."modules/sstars/functions/forms/batch_form.php";

function LoadTask() {

	$c="";

	/*
		THE FORM IS BEING SUBMITTED
	*/
	if (ISSET($_POST['FormSubmit'])) {
		$err=False; /* ASSUME NO ERRORS AS WE GO ALONG */
		$c.="Saving...<br>";
		$obj_bi=new BatchID; /* OBJECT TO CREATE A NEW BATCH */
		$obj_bi->GetFormPostedValues();

		/* IF THERE'S NO BATCH ID THEN WE'RE INSERTING A NEW RECORD */
		if (!ISSET($_POST['batch_id'])) {
			echo "ok";
			$result=$obj_bi->Add();
			if ($result) {
				$c.="Success!<br>\n";
				$c.="<a href='index.php?module=sstars&task=inventory&sub=batch_items&batch_id=".$obj_bi->GetInfo("batch_id")."'><img src='images/buttons/next.gif' border='0'></a>\n";
				//$show_form=False;
			}
			else {
				$c.="Error!<br>\n";
				$c.=$obj_bi->ShowErrors();
			}
		}
		else {
			$result=$obj_bi->Edit();
			if ($result) {
				$c.="Success!<br>\n";
			}
			else {
				$c.=$obj_bi->ShowErrors();
			}
		}
	}

	$c.=BatchForm();

	return $c;

}
?>