<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class FieldValidation {

	function __construct() {
		$this->data="";
	}

	function FormName($form_name) {
		$this->form_name=$form_name;
	}

	function OpenTag() {
		$this->data.="<script language='javascript'>\n";
		$this->data.="function ValidateForm(theform) {\n";
	}

	function ValidateFields($arr) {
		//$this->data.="function ValidateFields(theform){\n";
			$vals=explode(",", $arr);
			for ($i=0;$i<count($vals);$i++) {
				$this->data.="if (theform.$vals[$i].value == \"\"){\n";
					$this->data.="alert(\"One or more required fields, indicated by a *, are empty!\");\n";
					//$this->data.="document.theform.getElementById('".$vals[$i]."').className='error_input';\n";
					$this->data.="theform.".$vals[$i].".className='err_input';\n";
					$this->data.="theform.".$vals[$i].".focus();\n";
					$this->data.="return false;\n";
				$this->data.="}\n";
			}
		//$this->data.="}\n";
	}

	function SubmitOnce() {
		//$this->data.="function SubmitOnce(theform) {\n";
			// if IE 4+ or NS 6+
			$this->data.="if (document.all || document.getElementById) {\n";
				// hunt down "submit" and "reset"
				$this->data.="for (i=0;i<theform.length;i++) {\n";
					$this->data.="var tempobj=theform.elements[i];\n";
					$this->data.="if(tempobj.type.toLowerCase()==\"submit\") {\n";
						$this->data.="tempobj.disabled=true;\n";
					$this->data.="}\n";
				$this->data.="}\n";
			$this->data.="}\n";
		//$this->data.="}\n";
	}

	function OnlyInteger($fld) {
		$this->data.="var _x = theform.".$fld.".value;
					if (parseInt(_x) != _x) {
  					alert('Only numbers allowed');
  					theform.".$fld.".focus();
  					theform.".$fld.".select();
  					return false;
  				}";
	}

	function CloseTag() {
		$this->data.="}\n";
		$this->data.="</script>\n";
	}

	function Draw() {
		return $this->data;
	}
}
?>