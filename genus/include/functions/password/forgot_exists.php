<?php
/**
	THIS FUNCTION CHECKS IF A USER HAS ALREADY REQUESTED A FORGOT PASSWORD
**/

function ForgotExists($email_address) {

	$db=$GLOBALS['db'];
	$mysql_database=$GLOBALS['mysql_database'];

	$sql="SELECT 'x'
      FROM ".$mysql_database.".sb_forgot_password
      WHERE email_address = '".$email_address."'
      ";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		return True;
	}
	else {
		return False;
	}
}
?>