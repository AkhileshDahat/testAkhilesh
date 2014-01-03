<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/helpdesk/classes/significance_id.php";
require_once $GLOBALS['dr']."modules/helpdesk/functions/forms/significance.php";

function Add() {

	$c="";

	if (ISSET($_GET['subtask']) && $_GET['subtask'] == "edit" && ISSET($_GET['significance_id'])) {

		$significance_id=EscapeData($_GET['significance_id']);

		$obj_ci=new SignificanceID;
		$obj_ci->SetParameters($significance_id);

		$significance_name=$obj_ci->GetInfo("significance_name");
	}
	else {
		$significance_id="";
		$significance_name="";
	}

	/* SHOW THE FORM */
	$c.=Significance($significance_id,$significance_name);

	return $c;
}
?>