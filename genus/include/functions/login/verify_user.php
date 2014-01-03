<?php
/*
	THIS FUNCTION VERIFIES A USER'S CREDENTIALS BASED ON USERNAME AND PASSWORD
*/

/* ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function VerifyUserLocalDatabase($login_id, $password) {

	$db=$GLOBALS['db'];
	$mysql_database=$GLOBALS['mysql_database'];

	$sql="SELECT 'x'
      FROM ".$GLOBALS['database_prefix']."core_user_master
      WHERE login = '".$login_id."'
      AND password = MD5('".$password."')";
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		return True;
	}
	else {
		return False;
	}
}

function VerifyUserIMAP($server,$login_id,$password) {

	$mbox = imap_open("{."$server."}INBOX",$login_id,$password);
	if ($mbox) {
		return True;
	}
	else {
		return False;
	}
}
?>