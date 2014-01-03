<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_SSTARS_' ) or die( 'Direct Access to this location is not allowed.' );

function TopMenuItems($name,$img,$sel=False,$url="") {
	if ($sel) { $tb_bgcol="E4E9F4"; } else { $tb_bgcol="ffffff"; }
	if (EMPTY($url)) {
		$url="<a href='index.php?module=sstars&task=".strtolower($name)."'>";
	}
	else {
		$url="<a href='".$url."'>";
	}
	$s="<table class='teamspace' bgcolor='#".$tb_bgcol."'>\n";
		$s.="<tr>\n";
			$s.="<td><img src='images/nuvola/22x22/".$img.".png'></td>\n";
			$s.="<td>".$url.$name."</a></td>\n";
		$s.="</tr>\n";
	$s.="</table>\n";
	return $s;
}
?>