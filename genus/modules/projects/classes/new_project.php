<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."include/functions/db/row_exists.php";

class NewProject {
	var $last_created_project_id;
	function AddProject($project_code,$title,$description,$category_id,$status_id,$priority_id,$start_date,
					$estimated_completion_date,$estimated_cost,$percentage_completion,$workspace_id,$teamspace_id) {

		$db=$GLOBALS['db'];

		if ($this->ProjectCodeExists($workspace_id,$teamspace_id,$project_code)) { $this->Errors("Project Code Duplicate"); return False; }
		if (!RowExists("project_category_master","category_id",$category_id,"AND workspace_id='".$workspace_id."' AND teamspace_id='".$teamspace_id."'")) { $this->Errors("Invalid Category"); return False; }
		if (!RowExists("project_status_master","status_id",$status_id,"AND workspace_id='".$workspace_id."' AND teamspace_id='".$teamspace_id."'")) { $this->Errors("Invalid Status"); return False; }
		if (!RowExists("project_priority_master","priority_id",$priority_id,"AND workspace_id='".$workspace_id."' AND teamspace_id='".$teamspace_id."'")) { $this->Errors("Invalid Priority"); return False; }

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."project_master
					(project_code,title,description,category_id,status_id,priority_id,start_date,
					estimated_completion_date,estimated_cost,percentage_completion,workspace_id,teamspace_id)
					VALUES (
					'".$project_code."',
					'".$title."',
					'".$description."',
					'".$category_id."',
					'".$status_id."',
					'".$priority_id."',
					'".$start_date."',
					'".$estimated_completion_date."',
					'".$estimated_cost."',
					'".$percentage_completion."',
					'".$workspace_id."',
					'".$teamspace_id."'
					)";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			$this->last_created_project_id=$db->last_insert_id();
			return True;
		}
		else {
			$this->Errors("Bug");
			return False;
		}
	}

	function EditProject($project_id,$project_code,$title,$description,$category_id,$status_id,$priority_id,$start_date,
											$estimated_completion_date,$estimated_cost,$percentage_completion) {

		$db=$GLOBALS['db'];

		if (EMPTY($project_id)) { $this->Errors("Invalid Project");	return False; }
		if (EMPTY($project_code)) { $this->Errors("Invalid Code");	return False; }
		if (EMPTY($title)) { $this->Errors("Invalid Title");	return False; }
		if (EMPTY($category_id)) { $this->Errors("Invalid Category");	return False; }
		if (EMPTY($status_id)) { $this->Errors("Invalid Status");	return False; }
		if (EMPTY($priority_id)) { $this->Errors("Invalid Priority");	return False; }

		$sql="UPDATE ".$GLOBALS['database_prefix']."project_master
					SET project_code = '".$project_code."',
					title = '".$title."',
					description = '".$description."',
					category_id = '".$category_id."',
					status_id = '".$status_id."',
					priority_id = '".$priority_id."',
					start_date = '".$start_date."',
					estimated_completion_date = '".$estimated_completion_date."',
					estimated_cost = '".$estimated_cost."',
					percentage_completion = '".$percentage_completion."'
					WHERE project_id = '".$project_id."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->Errors("No changes recorded");
			return False;
		}
	}

	function CreatedProjectID() {
		return $this->last_created_project_id;
	}

	function EditCompany($user_id,$company_name,$position,$industry_id,$street,$city,$postal_code,$state,$country_id,$phone,$fax) {

		if (EMPTY($company_name)) { $this->Errors("Invalid Company Name");	return False; }
		if (EMPTY($industry_id)) { $this->Errors("Invalid Industry");	return False; }

		$this->db=$GLOBALS['db'];

		$sql="UPDATE ".$GLOBALS['database_prefix']."user_company_master
					SET company_name = '".$company_name."',
					position = '".$position."',
					industry_id = '".$industry_id."',
					street = '".$street."',
					city = '".$city."',
					postal_code = '".$postal_code."',
					state = '".$state."',
					country_id = '".$country_id."',
					phone = '".$phone."',
					fax = '".$fax."'
					WHERE user_id = '".$user_id."'
					";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->Errors("No changes recorded");
			return False;
		}
	}

	function ProjectCodeExists($workspace_id,$teamspace_id,$project_code) {
		$db=$GLOBALS['db'];
		$sql="SELECT 'x'
				FROM ".$GLOBALS['database_prefix']."project_master
				WHERE workspace_id = '".$workspace_id."'
				AND teamspace_id = '".$teamspace_id."'
				AND project_code = '".$project_code."'
				";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			return True;
		}
		else {
			return False;
		}
	}

	function Errors($err) {
		$this->errors.=$err."<br>";
	}

	function ShowErrors() {
		return $this->errors;
	}
}
?>