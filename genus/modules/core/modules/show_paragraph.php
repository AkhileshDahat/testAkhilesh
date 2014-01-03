<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/core/functions/browse/browse_paragraph.php";

function LoadTask() {

	$c="";
	$content=htmlentities($_GET['content']);
	$content=strtolower($content);


	//$c.="<table>\n";
		//$c.="<tr>\n";
			//$c.="<td valign='top'>\n";
			$c.="<img src='".$GLOBALS['wb']."images/banners/banner_".$content.".jpg'><br />";
			//$c.="<img src='".$GLOBALS['wb']."images/banners/banner_sms.jpg'><br />";
			$c.=BrowseParagraph($content);
			//$c.="</td>\n";
		//$c.="</tr>\n";
	//$c.="</table>\n";

	return $c;
}
?>