<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."modules/core/functions/forms/login_form.php");

function LoadTask() {

	$c="";

	$c.=LoginForm();

	return $c;
}
?>