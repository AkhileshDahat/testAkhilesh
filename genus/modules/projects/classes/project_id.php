<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class ProjectID {
	
	function __construct() {
		$this->debug=False;
	}
	public function SetParameters($transaction_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($transaction_id)) { $this->Errors("Invalid transaction"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->transaction_id=$transaction_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info();

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}
	
	private function Info() {

		$db=$GLOBALS['db'];

		$sql="SELECT pm.project_code, pm.title, pm.description, pm.category_id, pm.status_id, pm.priority_id, pm.start_date,
				pm.estimated_completion_date, pm.actual_completion_date, pm.estimated_cost, pm.actual_cost, pm.workspace_id,
				pm.teamspace_id, pm.percentage_completion, psm.status_name, pcm.category_name
				FROM ".$GLOBALS['database_prefix']."project_master pm, ".$GLOBALS['database_prefix']."project_status_master psm,
				".$GLOBALS['database_prefix']."project_category_master pcm
				WHERE pm.project_id = '".$project_id."'
				AND pm.workspace_id = ".$GLOBALS['workspace_id']."
				AND pm.teamspace_id ".$GLOBALS['teamspace_sql']."
				AND pm.status_id = psm.status_id
				AND pm.category_id = pcm.category_id
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
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
		if (ISSET($this->$v)) {
			return $this->$v;
		}
		else {
			return "";
		}
	}
	
	public function Add() {

		/* CHECKS */
		$this->Debug("Adding Project Now...");
		if (EMPTY($this->project_code)) { $this->Errors("Please enter a project code!"); return False; }
		if (EMPTY($this->title)) { $this->Errors("Please enter a title!"); return False; }
		if (EMPTY($this->description)) { $this->Errors("Please enter a description!"); return False; }
		if (EMPTY($this->category_id)) { $this->Errors("Please enter a category!"); return False; }
		if (EMPTY($this->status_id)) { $this->Errors("Please enter a status!"); return False; }		
				
		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."project_master
					(project_code,title,description,category_id,status_id,priority_id,start_date,estimated_completion_date,actual_completion_date,estimated_cost,actual_cost,
					workspace_id,teamspace_id,percentage_completion,date_added)
					VALUES (
					'".$this->project_code."',
					'".$this->title."',					
					'".$this->description."',
					'".$this->category_id."',
					'".$this->status_id."',
					'".$this->priority_id."',
					'".$this->start_date."',
					'".$this->estimated_completion_date."',
					'".$this->actual_completion_date."',
					'".$this->estimated_cost."',										
					'".$this->actual_cost."',										
					".$GLOBALS['workspace_id'].",
					".$GLOBALS['teamspace_id'].",
					'".$this->percentage_completion."',
						now()
					)";
		//echo $sql;
		$result=$db->query($sql);
		//$result=True; // enable for debugging
		if ($result) {			
			return True;
		}
		else {
			return False;
		}
	}
	
	public function GetFormPostedValues() {
		$all_form_fields=array("project_code","title","description","category_id","status_id","priority_id","start_date","estimated_completion_date","actual_completion_date",
														"estimated_cost","actual_cost","percentage_completion");

		for ($i=0;$i<count($all_form_fields);$i++) {

			//echo $_POST['application_id'];
			if (ISSET($_POST[$all_form_fields[$i]]) && !EMPTY($_POST[$all_form_fields[$i]])) {
				$this->SetVariable($all_form_fields[$i],$_POST[$all_form_fields[$i]]);
			}
			else {
				//echo "<br>".$all_form_fields[$i]."<br>";
				$this->$all_form_fields[$i]="";
			}
		}
	}
	
	public function Debug($desc) {
		if ($this->debug==True) {
			echo $desc."<br>\n";
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