<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/db/get_sequence_currval.php";

class SurveyID {

	function __construct() {
		$this->errors="";
	}

	public function SetParameters($survey_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($survey_id)) { $this->Errors("Invalid survey"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->survey_id=$survey_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info($survey_id);

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function Info($survey_id) {

		$this->db=$GLOBALS['db'];

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."survey_master
					WHERE survey_id = '".$survey_id."'
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

	public function GetSurveyIdFromPass($pw) {

		$db=$GLOBALS['db'];

		$sql="SELECT survey_id
					FROM ".$GLOBALS['database_prefix']."survey_master
					WHERE public_password = '".$pw."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				return $row['survey_id'];
			}
		}
		return False;
	}

	public function GetInfo($v) {
		return $this->$v;
	}

	public function Add() {

		/* CHECKS */
		//if (!RowExists("visitor_category_master","visitor_category_id","'".$this->visitor_category_id."'","AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Invalid category."); return False; }
		if (EMPTY($this->description)) { $this->Errors("Description must not be empty"); return False; }
		if (EMPTY($this->date_open)) { $this->Errors("Start date must not be empty"); return False; }
		if (EMPTY($this->date_closed)) { $this->Errors("End date must not be empty"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."survey_master
					(user_id,description,workspace_id,teamspace_id,date_open,date_closed,public_password)
					VALUES (
					".$_SESSION['user_id'].",
					'".$this->description."',
					".$GLOBALS['workspace_id'].",
					".$GLOBALS['teamspace_id'].",
					'".$this->date_open."',
					'".$this->date_closed."',
					substr(md5(rand()),1,7)
					)";
		$result=$db->query($sql);
		if ($result) {
			return True;
		}
		else {
			return False;
		}
	}

		public function AddQuestion() {

		/* CHECKS */
		//if (!RowExists("visitor_category_master","visitor_category_id","'".$this->visitor_category_id."'","AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Invalid category."); return False; }
		if (EMPTY($this->question)) { $this->Errors("Question must not be empty"); return False; }
		if (EMPTY($this->survey_id)) { $this->Errors("Invalid Survey"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."survey_question_master
					(survey_id,question)
					VALUES (
					'".$this->survey_id."',
					'".$this->question."'
					)";
		$result=$db->query($sql);
		if ($result) {
			return True;
		}
		else {
			return False;
		}
	}

	public function GetFormPostedValues() {
		/* THE ARRAY OF POSTED FIELDS */
		$req_fields=array("description","date_open","date_closed");

		for ($i=0;$i<count($req_fields);$i++) {

			//echo $_POST['application_id'];
			if (ISSET($_POST[$req_fields[$i]]) && !EMPTY($_POST[$req_fields[$i]])) {
				$this->SetVariable($req_fields[$i],EscapeData($_POST[$req_fields[$i]]));
			}
			else {
				//echo "<br>".$this->req_fields[$i]."<br>";
				$this->$req_fields[$i]="";
			}
		}
	}
	/* GETS FORM VALUES FOR QUESTIONS */
	public function GetFormPostedValuesQuestions() {
		/* THE ARRAY OF POSTED FIELDS */
		$req_fields=array("survey_id","question");

		for ($i=0;$i<count($req_fields);$i++) {

			//echo $_POST['application_id'];
			if (ISSET($_POST[$req_fields[$i]]) && !EMPTY($_POST[$req_fields[$i]])) {
				$this->SetVariable($req_fields[$i],EscapeData($_POST[$req_fields[$i]]));
			}
			else {
				//echo "<br>".$this->req_fields[$i]."<br>";
				$this->$req_fields[$i]="";
			}
		}
	}

	public function GetSurveyFormResults() {

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="SELECT question_id,question
					FROM ".$GLOBALS['database_prefix']."survey_question_master sqm
					WHERE survey_id = '".$this->survey_id."'
					";
		//echo $sql."<br>";
		$result = mysql_query($sql);
		if (!$result) { die('Invalid query: ' . mysql_error()); }
		while ($row = mysql_fetch_array($result)) {
			$answer_field="answer_".$row['question_id']	;
			//echo $row['question']." - ".$_POST[$answer_field]."<br>";
			$result_answer_q=$this->SaveAnswer($row['question_id'],$_POST[$answer_field]);
			if (!$result_answer_q) {
				$this->Errors("Unable to save");
				//return False;
			}
		}

	}

	public function SaveAnswer($question_id,$answer) {
		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."survey_answer_master
					(question_id,public_password,user_id,answer)
					VALUES (
					".$question_id.",
					'".$_SESSION['survey_password']."',
					'".$_SESSION['user_id']."',
					".$answer."
					)
					";
		//echo $sql."<br>";
		$result = mysql_query($sql);
		if (!$result) { $this->Errors(mysql_error()); }
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			return False;
		}
	}

	public function DeleteQuestion() {
		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="DELETE FROM ".$GLOBALS['database_prefix']."survey_question_master
					WHERE survey_id = '".$this->survey_id."'
					AND question_id = '".$this->question_id."'
					";
		//echo $sql."<br>";
		$result = mysql_query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			return False;
		}
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