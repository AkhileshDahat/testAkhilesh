<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function LogoutBar() {
	$setup=$GLOBALS['setup'];
	$s="<ul id=\"tablist\">\n";
		$s.="<li><a class=\"current\" href=\"index.php\" accesskey=\"h\"><span class=\"key\">H</span>ome</a></li>\n";
		//$s.="<li><a class=\"current\" href=\"index.php\" accesskey=\"l\"><span class=\"key\">L</span>ogin</a></li>\n";
		$s.="<li><a class=\"current\" onClick=\"getDataReturnTextDIV('".$GLOBALS['wb']."bin/ajax/login.php','left_articles')\" accesskey=\"l\"><span class=\"key\">L</span>ogin</a></li>\n";
		if ($setup->AllowSignup()=="y") {
			$s.="<li><a class=\"current\" href=\"index.php?module=signup\" accesskey=\"s\"><span class=\"key\">S</span>ignup</a></li>\n";
		}
		$s.="<li><a class=\"current\" href=\"index.php\" accesskey=\"r\"><span class=\"key\">R</span>ecover your password</a></li>\n";
		//$s.="<li><a class=\"current\" href=\"index.php?module=core&task=show_paragraph&content=about\" accesskey=\"a\"><span class=\"key\">A</span>bout</a></li>\n";
		$s.="<li><a class=\"current\" onClick=\"getDataReturnTextDIV('".$GLOBALS['wb']."bin/ajax/show_paragraph.php?module=core&task=show_paragraph&content=about','left_articles')\" accesskey=\"a\"><span class=\"key\">A</span>bout</a></li>\n";
		$s.="<li><a class=\"current\" onClick=\"getDataReturnTextDIV('".$GLOBALS['wb']."bin/ajax/show_paragraph.php?module=core&task=show_paragraph&content=privacy','left_articles')\" accesskey=\"p\"><span class=\"key\">P</span>rivacy</a></li>\n";
		//$s.="<li><a class=\"current\" href=\"index.php?module=core&task=show_paragraph&content=privacy\" accesskey=\"h\"><span class=\"key\">P</span>rivacy</a></li>\n";
	$s.="</ul>\n";
	return $s;
}
?>