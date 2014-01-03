<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/core/classes/role_id.php";
require_once $GLOBALS['dr']."modules/core/functions/forms/roles.php";

function Add() {

	$c="";

	if (ISSET($_GET['subtask']) && $_GET['subtask'] == "edit" && ISSET($_GET['role_id'])) {

		$role_id=EscapeData($_GET['role_id']);

		$obj_ri=new RoleID;
		$obj_ri->SetParameters($role_id);

		$role_name=$obj_ri->GetInfo("role_name");
	}
	else {
		$role_id="";
		$role_name="";
	}

	/* SHOW THE FORM */
	$c.=Roles($role_id,$role_name);

	return $c;
}
?>