<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class QuestionID {

	function __construct() {
		$this->errors="";
	}

	public function SetParameters($question_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($question_id)) { $this->Errors("Invalid survey"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->question_id=$question_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info($question_id);

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function Info($question_id) {

		$this->db=$GLOBALS['db'];

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."survey_question_master
					WHERE question_id = '".$question_id."'
					";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				/* HERE WE CALL THE FIELDS AND SET THEM INTO DYNAMIC VARIABLES */
				$arr_cols=$this->db->GetColumns($result);
				for ($i=1;$i<count($arr_cols);$i++) {
					$col_name=$arr_cols[$i];
					$this->$col_name=$row[$col_name];
				}
			}
		}
	}

	public function GetInfo($v) {
		return $this->$v;
	}

	public function SetVariable($variable,$variable_val) {
		$this->$variable=EscapeData($variable_val);
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}
}
?>