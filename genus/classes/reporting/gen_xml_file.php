<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class GenXMLFile {
	public function __construct() {
		/* MONTHS ARRAY */
		$arr_months_short=array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
		for ($i=0;$i<count($arr_months_short);$i++) {
			$this->arr_months_short[$i]=0;
		}
		$this->arr_months_short=$arr_months_short;
		/* CONTENT VARIABLE */
		$this->output_data="";

	}

	public function GenHead() {
		$this->output_data.="<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		$this->output_data.="<graph>\n";
		$this->output_data.="<general_settings bg_color=\"ffffff\" showAnchor=\"1\" showArea=\"0\" type_animation=\"1\">\n";
		$this->output_data.="<header text=\"Monthly Leave (last 3 years)\" font=\"Verdana\" color=\"000000\" size=\"18\" />\n";
		$this->output_data.="<subheader text=\"\" font=\"Verdana\" color=\"000000\" size=\"14\" />\n";
		$this->output_data.="<legend font=\"Verdana\" color=\"000000\" font_size=\"11\" />\n";
		$this->output_data.="<legend_popup font=\"Verdana\" bgcolor=\"FFFFE3\" font_size=\"10\" />\n";
		$this->output_data.="<Xheaders rotate=\"0\" color=\"000000\" size=\"10\" title=\"\" title_color=\"000000\" />\n";
		$this->output_data.="<Yheaders color=\"000000\" size=\"10\" title=\"\" title_rotate=\"90\" title_color=\"000000\" />\n";
		$this->output_data.="<grid showX=\"1\" showY=\"1\" persent_stepY_from_stepX=\"120\" grid_width=\"550\" grid_height=\"250\" grid_color=\"000000\" grid_alpha=\"20\" grid_thickness=\"1\" bg_color=\"ffffff\" bg_alpha=\"100\" alternate_bg_color=\"ffffff\" border_color=\"000000\" border_thickness=\"2\" />\n";
		$this->output_data.="</general_settings>\n";
	}

	public function GenLegendMonths() {
		$this->output_data.="<abscissa_data>\n";
		for ($i=0;$i<count($this->arr_months_short);$i++) {
			$this->output_data.="<x name=\"".$this->arr_months_short[$i]."\" value=\"20\" />\n";
		}
		$this->output_data.="</abscissa_data>\n";
	}

	public function GenTotals($seriesName,$color,$size,$arr_vals) {
		$this->output_data.="<ordinate_data seriesName=\"Current Year\" color=\"000000\" size=\"3.5\">\n";
		for ($i=0;$i<count($this->arr_vals);$i++) {
			$this->output_data.="<y value=\"".$this->arr_vals[$i]."\" />\n";
		}
		$this->output_data.="</ordinate_data>\n";
	}

	public function GenFooter() {
		$this->output_data.="</graph>\n";
	}

	public function SaveToDir() {
		$result=file_put_contents($GLOBALS['dr']."/bin/reporting/xml/".$_SESSION['sid'].".xml",$this->output_data);
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