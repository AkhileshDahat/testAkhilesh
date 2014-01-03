<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

if (ISSET($_GET)) {
	foreach ($_GET as $key => $value) {
		$_GET[$key]=htmlentities($_GET[$key]);
	}
}
if (ISSET($_POST)) {
	foreach ($_POST as $key => $value) {
		if (!is_array($_POST[$key])) {
			$_POST[$key]=htmlentities($_POST[$key]);
		}
	}
}
if (ISSET($_COOKIE)) {
	foreach ($_COOKIE as $key => $value) {
		$_COOKIE[$key]=htmlentities($_COOKIE[$key]);
	}
}
/*
if (ISSET($_SERVER)) {
	foreach ($_SERVER as $key => $value) {
		if (ISSET($_SERVER[$key])) {
			$_SERVER[$key]=htmlentities($_SERVER[$key]);
		}
	}
}
*/
?>