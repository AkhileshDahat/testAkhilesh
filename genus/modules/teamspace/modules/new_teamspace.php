<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/teamspace/classes/teamspace_id.php";
require_once $GLOBALS['dr']."modules/teamspace/functions/forms/new_teamspace.php";

function LoadTask() {

	$c="";

	if (ISSET($_POST['FormSubmit']) && $_POST['FormSubmit']=="Save") {
		$ti=new TeamspaceID();
		$result=$ti->Add($_POST['name'],$_POST['description']);
		if ($result) {
			$c.="Success";
		}
		else {
			$c.=$ti->ShowErrors();
		}
	}

	$c.=NewTeamspace();

	return $c;
}
?>