<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/helpdesk/classes/tag_id.php";
require_once $GLOBALS['dr']."modules/helpdesk/functions/forms/tags.php";

function Add() {

	$c="";

	if (ISSET($_GET['subtask']) && $_GET['subtask'] == "edit" && ISSET($_GET['tag_id'])) {

		$tag_id=EscapeData($_GET['tag_id']);

		$obj_ci=new TagID;
		$obj_ci->SetParameters($tag_id);

		$tag_name=$obj_ci->GetInfo("tag_name");
	}
	else {
		$tag_id="";
		$tag_name="";
	}

	/* SHOW THE FORM */
	$c.=Tags($tag_id,$tag_name);

	return $c;
}
?>