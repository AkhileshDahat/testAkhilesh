<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function LoginFormBasic() {
	$setup=$GLOBALS['setup'];

	$s="<form method=post name='s_login' action='index.php?".$_SERVER['QUERY_STRING']."'>\n";
	$s.="Email:\n";
	$s.="<input type='text' name='username' value='' maxlength='50' size='30' tabindex='1'>\n";
	$s.="Password:\n";
	$s.="<input type='password' name='password' maxlength='50' size='15' tabindex='2' autocomplete='off'>\n";
	$s.="Remember:\n";
	$s.="<input type='checkbox' name='remember_me' value='y'>\n";
	$s.="<input type='submit' value='Login' tabindex='3'>\n";
	$s.="</form>\n";

	$s.="<script language='JavaScript'>\n";
	$s.="document.s_login.email_address.focus();\n";
	$s.="</script>\n";

	return $s;
}

function LoginForm() {
	$setup=$GLOBALS['setup'];

	$s="<form method=post name='s_login' action='index.php?".$_SERVER['QUERY_STRING']."'>\n";
	$s.="<br /><table align='center' class='login' height='300'>\n";
		$s.="<tr class=subheader>\n";
			$s.="<td colspan=2 align='center'>Login Here</td>\n";
		$s.="</tr>\n";
		$s.="<tr>\n";
			$s.="<td>Email: </td>\n";
			$s.="<td><input type='text' name='username' value='' maxlength='50' size='30' tabindex='1'></td>\n";
		$s.="</tr>\n";
		$s.="<tr>\n";
			$s.="<td>Password: </td>\n";
			$s.="<td><input type='password' name='password' value='' maxlength='50' size='30' tabindex='2' autocomplete='off'></td>\n";
		$s.="</tr>\n";
		$s.="<tr>\n";
			$s.="<td colspan=2 align='right'><input type='submit' class=buttonstyle1 value='Login' tabindex='3'></td>\n";
		$s.="</tr>\n";
	$s.="</table>\n";
	$s.="</form>\n";

	$s.="<script language='JavaScript'>\n";
	$s.="document.s_login.username.focus();\n";
	$s.="</script><br />\n";

	return $s;
}

function LoginFormCookie() {
	$setup=$GLOBALS['setup'];
	$username=$_COOKIE['mvh_username'];
	$s="<table class='teamspace' cellpadding='15' cellspacing='0' height='300' border='0'>\n";
		$s.="<tr>\n";
			$s.="<td align='center'><img src='images/usericons/babelfish.png'></td>\n";
		$s.="</tr>\n";
		$s.="<tr class='modulehead'>\n";
			$s.="<td align='center'>".$username."</td>\n";
		$s.="</tr>\n";
		$s.="<form method=post name='s_login' action='".EscapeData($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'])."'>\n";
		$s.="<input type='hidden' name='username' value='".$username."'>\n";
		$s.="<tr>\n";
			$s.="<td align='center'>Password: <input type='password' name='password' maxlength='50' size='15' autocomplete='off'><input type='image' src='images/nuvola/22x22/actions/player_end.png'></td>\n";
		$s.="</tr>\n";
		$s.="</form>\n";
		if ($setup->AllowForgotPassword()=="y") {
			$s.="<tr>\n";
				$s.="<td colspan='2'><img src='".$GLOBALS['wb']."images/nuvola/22x22/apps/kuser.png'><a href='index.php?module=signup&task=recover_password'>Recover your passwod</a></td>\n";
			$s.="</tr>\n";
		}
		$s.="<tr>\n";
			$s.="<td colspan='2'><a href='index.php?dtask=remove_remember_me'>[Remove this account]</a></td>\n";
		$s.="</tr>\n";
	$s.="</table>\n";
	$s.="<script language='JavaScript'>\n";
	$s.="document.s_login.email_address.focus();\n";
	$s.="</script>\n";
	return $s;
}
?>