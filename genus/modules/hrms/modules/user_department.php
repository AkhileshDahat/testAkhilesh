<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."modules/core/classes/workspace_users.php");
require_once($GLOBALS['dr']."modules/hrms/functions/forms/add_department.php");

function LoadTask() {

	$c="";

	$c.=AddDepartment();

	return $c;
}
?>