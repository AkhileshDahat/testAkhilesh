<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/simpledoc/functions/forms/add.php";
require_once $GLOBALS['dr']."modules/simpledoc/classes/document_id.php";

function LoadTask() {

	$ui=$GLOBALS['ui'];
	$show_form=True;
	$c="";

	/* FORM PROCESSING */
	if (ISSET($_POST['FormSubmit'])) {
			
		$show_form=False;
		$obj_di=new DocumentID;
		$obj_di->GetUploadedFile();
		$result=$obj_di->Add();
		if ($result) { $c.="ok"; } else { $c.="failed"; $c.=$obj_di->ShowErrors(); }
	}
	else {
	}

	/*
		DESIGN THE FORM
	*/
	if ($show_form) {
		$c.=Add();
	}

	return $c;
}
?>