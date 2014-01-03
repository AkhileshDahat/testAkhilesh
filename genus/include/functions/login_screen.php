<?php
function LoginScreen() {
	$c="<table class='table_dotted' bgcolor='#CCCCFF'>\n";
		$c.="<form method='post' action='bin/login/login.php'>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2'>Login Now:</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td width='30%'>Email Address:</td>\n";
			$c.="<td><input type='text' name='email_address' size='20' maxlength='30' /></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Password:</td>\n";
			$c.="<td><input type='password' name='password' size='10' maxlength='10' /></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2'><input type='submit' value='Login!' class='submit' /></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2'><a href='index.php?module=forgot_password'>Forgotten your password?</a></td>\n";
		$c.="</tr>\n";
		$c.="</form>\n";
	$c.="</table>\n";
	return $c;
}
?>