<?php

class DrawMenu {

	function DrawTopMenu($id) {
		$this->c="<div id='".$id."' style='display:none;'>\n";
	}

	function DrawContent($class,$img="",$url,$target="parent",$desc) {
		if (EMPTY($class)) { $class="contextmenuitem"; }
		if (!EMPTY($url)) { $url="url='".$url."'"; }
		$this->c.="<div class='".$class."' ".$url." target='".$target."' title='".$desc."'>".$img."&nbsp;".$desc."</div>\n";
	}

	function DrawBottomMenu() {
		$this->c.="</div>\n";
	}

	function DrawMenuFinal() {
		return $this->c;
	}


}
?>