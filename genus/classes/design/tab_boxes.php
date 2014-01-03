<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/string/initcap.php";

class TabBoxes {

	function DrawBoxes($arr,$dir) {
		$this->arr=$arr;
		$this->dir=$dir;
		$c=$this->Javascript();
		$c.=$this->DrawTopMenu();
		$c.=$this->Content();

		return $c;
	}

	function DrawTopMenu() {

		$c="<table class='plain' bgcolor=#dedede>\n";
			$c.="<tr>\n";
			$width=(100/count($this->arr));
			$width = round($width,2);
			for ($i=0;$i<count($this->arr);$i++) {
				$c.="<td align='center'><a href='#stayhere' id='Head_".$this->arr[$i]."' onClick=\"Display".$this->arr[$i]."()\">".InitCap($this->arr[$i])."</a></td>\n";
				$c.="<td bgcolor=white> </td>\n";
			}
			$c.="</tr>\n";
		$c.="</table>\n";

		return $c;
	}
	function Javascript() {

		$c="<script language='JavaScript'>\n";

			for ($i=0;$i<count($this->arr);$i++) {
				$c.="function Display".$this->arr[$i]."() {\n";
				$c.="document.getElementById('".$this->arr[$i]."').className='showBlk';\n";
				for ($j=0;$j<count($this->arr);$j++) {
					if ($this->arr[$j] != $this->arr[$i]) {
						$c.="document.getElementById('".$this->arr[$j]."').className='hideBlk';\n";
					}
				}
				$c.="}\n";
			}
		$c.="</script>\n";

		return $c;
	}

	function Content() {
		$c="";
		for ($i=0;$i<count($this->arr);$i++) {
			if ($i==0) { $class="showBlk"; } else { $class="hideBlk"; }
			$c.="<div id='".$this->arr[$i]."' class='".$class."'>\n";
			require_once $this->dir.$this->arr[$i].".php";
			$v_funct_inc=STR_REPLACE("_","",$this->arr[$i]);
			$c.=$v_funct_inc();
			//$file_function=$v_funct_inc;
			//$c.=$file_function(); /* THIS ALLOWS US TO CALL A DYNAMIC FUNCTION NAME BASED ON WHAT THE FILE IS CALLED */
			$c.="</div>\n";
		}
		return $c;
	}

	function BlockShow($block) {
		$c="<script language='Javascript'>\n";
		$c.="Display".$block."();\n";
		$c.="</script>\n";

		return $c;
	}
}
?>