<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function Menu($user_id) {
	$c="<table class='plain' width='150'>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2' class='bold'>SSTARS Menu</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td width='16'><img src='images/nuvola/16x16/actions/gohome.png'></td>\n";
			$c.="<td><a href='index.php?module=sstars&parent_id=".$_GET['parent_id']."'>Home</a></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td width='16'><img src='images/nuvola/16x16/apps/kwrite.png'></td>\n";
			$c.="<td><a href='index.php?module=sstars&task=search'>Apply</a></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td width='16'><img src='images/nuvola/16x16/actions/view_right.png'></td>\n";
			$c.="<td><a href='index.php?module=sstars&task=search'>Orders</a></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td width='16'><img src='images/nuvola/16x16/apps/korganizer_todo.png'></td>\n";
			$c.="<td><a href='index.php?module=sstars&task=add_category&parent_id=".$_GET['parent_id']."'>Inventory</a></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td width='16'><img src='images/nuvola/16x16/apps/kcmpartitions.png'></td>\n";
			$c.="<td><a href='index.php?module=sstars&task=search'>Reports</a></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td width='16'><img src='images/nuvola/16x16/apps/background.png'></td>\n";
			$c.="<td><a href='index.php?module=sstars&task=search'>Dashboard</a></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td width='16'><img src='images/nuvola/16x16/apps/agent.png'></td>\n";
			$c.="<td><a href='modules/sstars/bin/category/publish_category.php?parent_id=".$_GET['parent_id']."'>Settings</a></td>\n";
		$c.="</tr>\n";
	$c.="</table>\n";

	return $c;
}