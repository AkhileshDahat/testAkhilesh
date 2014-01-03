<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class FusionColumn2D {
	public function __construct() {
		$this->arr_colors=array("175BE3","FFB31A","3C3C3C","FFFD1A","FF1A68","74F418","FF6E1A","8215D3","00B864","FFCC00","5800A2","FF7200","004EB0","EA0036","A2D400");
	}

	public function SetVar($var,$val) {
		$this->$var=$val;
	}

	public function GenHead() {
		$this->output_data.="<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		//$this->output_data.="<graph xAxisName='Month' decimalPrecision='0' formatNumberScale='0'>\n";
		$this->output_data.="<graph decimalPrecision='0' formatNumberScale='0'>\n";


	}

	public function GenLegendDB($sql) {
		$db = $GLOBALS['db'];
		$result = $db->Query($sql);
		$count = 0;
		while ($row = $db->FetchArray($result)) {
			$this->output_data.="<set name='".$row['legend']."' value='".$row['total']."' color='".$this->arr_colors[$count]."' />\n";
			$count++;
		}
	}

	public function GenFooter() {
		$this->output_data.="</graph>\n";
	}

	public function SaveToDir() {
		$result=file_put_contents($GLOBALS['dr']."/bin/reporting/xml/column2d_".$_SESSION['sid'].".xml",$this->output_data);
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