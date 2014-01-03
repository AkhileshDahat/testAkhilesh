<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class GenXMLFilePie3D {
	public function __construct() {
		/* MONTHS ARRAY */
		//$this->arr_data_index=array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
		$this->arr_data_values=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
		$this->arr_colors=array("175BE3","FFB31A","3C3C3C","FFFD1A","FF1A68","74F418","FF6E1A","8215D3","00B864","FFCC00","5800A2","FF7200","004EB0","EA0036","A2D400");
		//for ($i=0;$i<count($arr_months_short);$i++) {
			//$this->arr_months_short[$i]=0;
		//}
		//$this->arr_months_short=$arr_months_short;
		/* CONTENT VARIABLE */
		$this->output_data="";
	}

	public function SetVar($var,$val) {
		$this->$var=$val;
	}

	public function GenHead() {
		$this->output_data.="<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		$this->output_data.="<graph>\n";
		$this->output_data.="  <general_settings bg_color=\"ffffff\" />\n";
		$this->output_data.="  <header text=\"".$this->header."\" font=\"Verdana\" color=\"000000\" size=\"18\" />\n";
		$this->output_data.="  <subheader text=\"\" font=\"Verdana\" color=\"000000\" size=\"14\" />\n";
		$this->output_data.="  <legend font=\"Verdana\" font_color=\"000000\" font_size=\"11\" bgcolor=\"FFFFFF\" alternate_bg_color=\"FFF9E1\" border_color=\"BFBFBF\" />\n";
		$this->output_data.="  <legend_popup font=\"Verdana\" bgcolor=\"FFFFE3\" font_size=\"10\" />\n";
		$this->output_data.="  <pie_chart radius=\"120\" height=\"35\" angle_slope=\"45\" alpha_sides=\"60\" alpha_lines=\"20\"/>\n";
	}

	public function GenLegend() {
		$this->output_data.="\n";
		for ($i=0;$i<count($this->arr_data_index);$i++) {
			$this->output_data.="    <data name=\"".$this->arr_data_index[$i]."\" value=\"".round($this->arr_data_values[$i],2)."\" color=\"".$this->arr_colors[$i]."\" />\n";
		}
	}

	public function GenFooter() {
		$this->output_data.="</graph>\n";
	}

	public function SaveToDir() {
		$result=file_put_contents($GLOBALS['dr']."/bin/reporting/xml/pie3d_".$_SESSION['sid'].".xml",$this->output_data);
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