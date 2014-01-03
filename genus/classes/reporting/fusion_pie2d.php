<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class FusionPie2D {
	public function __construct() {

	}

	public function SetVar($var,$val) {
		$this->$var=$val;
	}

	public function GenHead() {
		$this->output_data.="<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		$this->output_data.="<graph showNames='1'  decimalPrecision='0'  >\n";
	}

	public function GenLegendDB($sql) {
		$db = $GLOBALS['db'];
		$result = $db->Query($sql);
		while ($row = $db->FetchArray($result)) {
			$this->output_data.="<set name='".$row['legend']."' value='".$row['total']."' isSliced='0'/>\n";
		}
	}

	public function GenFooter() {
		$this->output_data.="</graph>\n";
	}

	public function SaveToDir($f="") {
		$result=file_put_contents($GLOBALS['dr']."/bin/reporting/xml/pie2d_$f".$_SESSION['sid'].".xml",$this->output_data);
	}

	public function GetVar($var) {
		if (ISSET($this->$var)) {
			return $this->$var;
		}
		else {
			return "False";
		}
	}
}
?>