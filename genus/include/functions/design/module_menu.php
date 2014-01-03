<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."include/functions/string/initcap.php";

function ModuleMenu($head,$module_id,$arr_menu,$arr_images="") {
	$ui=$GLOBALS['ui'];
	$wb=$GLOBALS['wb'];
	$c="<table class='plain'>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2' class='bold'>".$head."</td>\n";
		$c.="</tr>\n";
		/* LOOP ALL THE ITEMS IN THE MENU ARRAY */
		for ($i=0;$i<count($arr_menu);$i++) {
			/* CHECK THE ACL FOR THIS MODULE */
			//echo $GLOBALS['wui']->RoleID()."<br>";
			//echo $module."<br>";
			//echo $arr_menu[$i]."<br>";
			if (CheckAccess($GLOBALS['wui']->RoleID(),$module_id,$arr_menu[$i])) {
				$friendly=InitCap($arr_menu[$i]);
				if (defined( '_VALID_MVH_MOBILE_' )) {
					$c.="<tr><td colspan='2'>+<a href='index.php?module=".EscapeData($_GET['module'])."&task=".$arr_menu[$i]."'>".$friendly."</a></td></tr>";
				}
				else {
					$c.="<tr>\n";
						$c.="<td width='16'><img src='".$wb."images/".$arr_images[$i]."'></td>\n";
						$c.="<td><a href='index.php?module=".EscapeData($_GET['module'])."&task=".$arr_menu[$i]."'>".$friendly."</a></td>\n";
					$c.="</tr>\n";
				}
			}
		}
	$c.="</table>\n";
	return $c;
}
?>