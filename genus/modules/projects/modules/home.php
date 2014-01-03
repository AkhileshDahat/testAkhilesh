<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/design/home_task_info.php";

function LoadTask() {

	$c="";
	$c.=HomeTaskInfo();

	return $c;

}
?>