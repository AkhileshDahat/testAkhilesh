<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."include/functions/design/module_menu_dynamic.php");

function Menu() {
	//print_r($GLOBALS['mainmenu']);
	return ModuleMenuDynamic($GLOBALS['module_id'],EscapeData($_GET['module']),$GLOBALS['mainmenu']);
}