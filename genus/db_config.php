<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* DATABASE TO USE */
require_once $dr."classes/db/mysql.php";
$db=new mysql;//New object
$result = $db->Connect($database_hostname,$database_port,$database_name,$database_user,$database_password);
if (!$result) {
	echo "Database connection failed";
	die();
}

/* DATABASE SESSIONS */
//require_once $GLOBALS['dr']."include/functions/db/session.php";

/* SESSION HANDLER */
//session_set_save_handler("SessionOpen", "SessionClose", "SessionRead", "SessionWrite", "SessionDestroy", "SessionCleanUp");
session_start();
?>