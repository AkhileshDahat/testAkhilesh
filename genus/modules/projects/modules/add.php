<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/projects/functions/forms/add_project.php";
require_once $GLOBALS['dr']."modules/projects/classes/project_id.php";

function LoadTask() {

	$c="";
	
	/* FORM PROCESSING */
	if (ISSET($_POST['FormSubmit'])) {
		$show_form=False;
		$obj_pi=new ProjectID;
		$obj_pi->GetFormPostedValues();
		$result=$obj_pi->Add();
		if ($result) { $c.="ok"; } else { $c.="failed"; }
	}
	else {
	}

	
	$c.=AddProject();

	return $c;

}
?>