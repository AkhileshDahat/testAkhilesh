<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/simpledoc/functions/browse/view.php";
require_once $GLOBALS['dr']."modules/simpledoc/classes/document_id.php";


function LoadTask() {

	$c="";
	
	if (ISSET($_GET['subtask'])) {
		if ($_GET['subtask']=="delete") {
			$obj_di=new DocumentID;
			$obj_di->SetParameters($_GET['document_id']);
			$result=$obj_di->Delete();
			if ($result) {
				$c.="Success";
			}
			else {
				$c.="Failed";
				$c.=$obj_di->ShowErrors();
			}
		}
	}
	
	/*
		DESIGN THE FORM
	*/

	$c.=View();

	return $c;
}
?>