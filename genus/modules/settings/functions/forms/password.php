<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

function PasswordForm() {
	$setup=$GLOBALS['setup'];

	$s="<form method=post name='s_reset' action='index.php?module=settings&task=password'>\n";
	$s.="<br /><table align='center' class='login' height='300'>\n";
		$s.="<tr class=subheader>\n";
			$s.="<td colspan=2 align='center'>RESET USER PASSWORD</td>\n";
		$s.="</tr>\n";
		$s.="<tr>\n";
			$s.="<td>Password: </td>\n";
			$s.="<td><input type='password' name='password' value='' maxlength='50' size='30' tabindex='2' autocomplete='off'></td>\n";
		$s.="</tr>\n";
		$s.="<tr>\n";
			$s.="<td>Repeat Password: </td>\n";
			$s.="<td><input type='password' name='r_password' value='' maxlength='50' size='30' tabindex='2' autocomplete='off'></td>\n";
		$s.="</tr>\n";
		$s.="<tr>\n";
			$s.="<td colspan=2 align='right'><input type='submit' name='submit' class=buttonstyle1 value='RESET' tabindex='3'></td>\n";
		$s.="</tr>\n";
	$s.="</table>\n";
	$s.="</form>\n";

	$s.="<script language='JavaScript'>\n";
	$s.="document.s_login.user_id.focus();\n";
	$s.="</script>\n";

	return $s;
}

?>