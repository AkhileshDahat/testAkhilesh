<?php
function ErrorHandler($errno, $errstr, $errfile, $errline) {
  echo ErrorDisplay($errno, $errstr, $errfile, $errline);
}

function ErrorDisplay($errno, $errstr, $errfile, $errline) {
	$c="<table align='center' border=1 bordercolor=#336699 bgcolor=#f4f4f4>\n";
		$c.="<tr height='50'>\n";
			$c.="<td colspan='2'><h1>We're sorry, an error has occured</h1></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' colspan='2'>
			This application has hit a possible bug. Your last action has been halted in an effort to fix the problem.<br>
		  The problem is being recorded and reported.
			</td>\n";
		$c.="</tr>\n";
		$c.="<tr height='50'>\n";
			$c.="<td colspan='2'><b>Some debugging information will follow</b></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td><b>Error Number:</b></td>\n";
			$c.="<td>$errno</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td><b>Error String:</b></td>\n";
			$c.="<td>$errstr</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td><b>Error File:</b></td>\n";
			$c.="<td>$errfile</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td><b>Error Line:</b></td>\n";
			$c.="<td>$errline</td>\n";
		$c.="</tr>\n";
	$c.="</table>\n";
	die($c);
	return $c;
}
?>