<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class LeftBar {
	
	public function __construct() {
		$this->content="<table width=100%>";
		//$this->SearchBarTop();		
		//$this->DropDownList();		
		//$this->InputBox();				
		$this->DrawFoot();
	}
		
	function SearchBarTop() {
		$this->content.="<tr>\n";
			$this->content.="<td bgcolor=#dedede>Search</td>\n";
		$this->content.="</tr>\n";
	}
	
	function DropDownList() {
		$arr=array("Users","Documents");
		$this->content.="<tr>\n";
			$this->content.="<td>";
			$this->content.="<select name='searchitem'>\n";
			for ($i=0;$i<count($arr);$i++) {
				$this->content.="<option value='".$arr[$i]."'>".$arr[$i]."</option\n";
			}
			$this->content.="</select>\n";
			$this->content.="</td>\n";
		$this->content.="</tr>\n";
	}
	
	function InputBox() {
		
		$this->content.="<tr>\n";
			$this->content.="<td><input type='text' name='search'></td>\n";
		$this->content.="</tr>\n";
	}
	
	/*
	public function SetVar($v,$val) {
		$this->$v=$val;
	}
	*/

	public function DrawFoot() {
		$this->content.="</table>";
	}
	public function Draw() {
		return $this->content;
	}
}
?>