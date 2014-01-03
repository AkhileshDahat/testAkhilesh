<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/string/initcap.php";

function ModuleMenuDynamic($module_id,$module_name,$items,$layout) {
	$ui=$GLOBALS['ui'];
	$dr=$GLOBALS['dr'];
	$wb=$GLOBALS['wb'];
	$module_id=$GLOBALS['module_id'];
	$c="<table class='plain' width='150'>\n";
		/*
		$c.="<tr>\n";
			$c.="<td colspan='3' class='bold'>".InitCap($module_name)." Menu</td>\n";
		$c.="</tr>\n";
		*/
		/* LOOP ALL THE ITEMS IN THE MENU ARRAY */
		for ($i=0;$i<count($items);$i++) {
			/* CHECK THE ACL FOR THIS MODULE */
			//echo $GLOBALS['wui']->RoleID()."<br>";
			//echo $module."<br>";
			//echo $arr_menu[$i]."<br>";
			$task_img_desc=STRTOLOWER($items[$i]);

			$task_img_desc=STR_REPLACE(" ","_",$task_img_desc);
			//echo $task_img_desc."<br>";

			if (CheckAccess($GLOBALS['wui']->RoleID(),$module_id,$task_img_desc)) {
				$friendly=InitCap($items[$i]);

				if (defined( '_VALID_MVH_MOBILE_' )) {
					$c.="<tr><td colspan='2'>+<a href='index.php?module=".$module_name."&task=".$task_img_desc."'>".$friendly."</a></td></tr>";
				}
				else {
					$icon_file=$dr."modules/".$module_name."/images/default/".$task_img_desc.".png";
					$icon_http="modules/".$module_name."/images/default/".$task_img_desc.".png";
					//echo $icon."<br>";
					if (file_exists($icon_file)) {
						$icon_file=$icon_http;
					}
					else {
						$icon_file=$wb."images/nuvola/16x16/actions/view_icon.png";
					}
					if (ISSET($_GET['task']) && $_GET['task']==$task_img_desc) {
						$arrow="<img src='images/nuvola/16x16/actions/player_play.png'>";
						$bgcolor="#dedede";
					}
					else {
						$arrow="";
						$bgcolor="#ffffff";
					}

					$c.="<tr>\n";
						$c.="<td width='16'>".$arrow."</td>\n";
						$c.="<td width='16'><img src='".$icon_file."'></td>\n";
						$c.="<td bgcolor='".$bgcolor."' width='134'><a href='index.php?module=".$module_name."&task=".$task_img_desc."'>".$friendly."</a></td>\n";
					$c.="</tr>\n";
				}
			}
		}
	$c.="</table>\n";
	return $c;
}
?>