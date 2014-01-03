<?php
/**
	THIS FUNCTION ADDS A NEW RECORD FOR FORGOTTEN PASSWORDS
**/

function AddForgot($email_address,$code) {

	$db=$GLOBALS['db'];
	$mysql_database=$GLOBALS['mysql_database'];

	$sql="INSERT INTO ".$mysql_database.".sb_forgot_password
      (email_address, code, date_requested)
      VALUES (
      '".$email_address."',
      '".$code."',
      sysdate()
      )
      ";
	$result = $db->query($sql);
	if ($db->affected_rows() > 0) {
		return True;
	}
	else {
		return False;
	}
}
?>