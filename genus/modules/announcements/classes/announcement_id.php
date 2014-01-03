<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class AnnouncementID {

	function __construct() {
		$this->debug=False;		
	}
	public function SetParameters($announcement_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($announcement_id)) { $this->Errors("Invalid announcement"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->announcement_id=$announcement_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info();

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT am.*,um.full_name
					FROM ".$GLOBALS['database_prefix']."announcements_master am,".$GLOBALS['database_prefix']."core_user_master um
					WHERE am.announcement_id = ".$this->announcement_id."
					AND am.workspace_id = ".$GLOBALS['workspace_id']."
					AND am.teamspace_id ".$GLOBALS['teamspace_sql']."					
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
		if (ISSET($this->$v)) {
			return $this->$v;
		}
		else {
			return "";
		}
	}

	public function Add() {

		/* CHECKS */
		$this->Debug("Sending SMS now");
		if(EMPTY($this->title)) { $this->Errors("Please enter a title!"); return False; }
		if(EMPTY($this->message)) { $this->Errors("Please enter a message!"); return False; }
				
		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."announcements_master
					(workspace_id,teamspace_id,title,message,date_added,user_id)
					VALUES (
					".$GLOBALS['workspace_id'].",
					".$GLOBALS['teamspace_id'].",					
					'".$this->title."',
					'".$this->message."',					
					now(),					
					".$_SESSION['user_id']."					
					)";
		//echo $sql;
		$result=$db->query($sql);
		//$result=True; // enable for debugging
		if ($result) {
			$this->announcement_id=$db->LastInsertID();						
			return True;
		}
		else {
			return False;
		}
	}	
	
	public function GetFormPostedValues() {
		$all_form_fields=array("title","message");

		for ($i=0;$i<count($all_form_fields);$i++) {

			//echo $_POST['announcement_id'];
			if (ISSET($_POST[$all_form_fields[$i]]) && !EMPTY($_POST[$all_form_fields[$i]])) {
				$this->SetVariable($all_form_fields[$i],$_POST[$all_form_fields[$i]]);
			}
			else {
				//echo "<br>".$all_form_fields[$i]."<br>";
				$this->$all_form_fields[$i]="";
			}
		}
	}

	public function SetVariable($variable,$variable_val) {
		$this->$variable=EscapeData($variable_val);
	}

	public function Debug($desc) {
		if ($this->debug==True) {
			echo $desc."<br>\n";
		}
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}
}
?>