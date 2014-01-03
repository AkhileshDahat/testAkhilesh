<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/db/get_sequence_currval.php";
require_once $GLOBALS['dr']."include/functions/acl/check_create_acl_task_exists.php";

class TeamspaceID {

	function __construct() {

	}
	public function SetParameters($teamspace_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($teamspace_id)) { $this->Errors("Invalid teamspace"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->teamspace_id=$teamspace_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info($teamspace_id);

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function Info($teamspace_id) {

		$db=$GLOBALS['db'];

		$sql="SELECT name,description
					FROM ".$GLOBALS['database_prefix']."core_teamspace_master
					WHERE teamspace_id = '".$teamspace_id."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				/* HERE WE CALL THE FIELDS AND SET THEM INTO DYNAMIC VARIABLES */
				$arr_cols=$db->GetColumns($result);
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

	public function Add($name,$description) {

		/* CHECKS */
		if(!preg_match("([a-zA-Z0-9])",$name))  { $this->Errors("Invalid teamspace name. Please use only alpha-numeric values!"); return False; }
		if (RowExists("core_teamspace_master","name","'".$name."'","AND workspace_id=".$GLOBALS['workspace_id'])) { $this->Errors("Teamspace name exists. Please choose another."); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_teamspace_master
					(workspace_id,name,description)
					VALUES (
					".$GLOBALS['workspace_id'].",
					'".EscapeData($name)."',
					'".EscapeData($description)."'
					)";
		$result=$db->query($sql);
		if ($result) {
			$this->teamspace_id=$db->LastInsertID();
			$this->AddModules();
			$this->AddUserRole();
			return True;
		}
		else {
			return False;
		}
	}
	public function Delete() {

		/* CHECKS */
		if (!$this->parameter_check) { $this->Errors("Teamspace ID deletion failed!"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="DELETE FROM ".$GLOBALS['database_prefix']."core_teamspace_master
					WHERE teamspace_id = '".$this->teamspace_id."'
					AND workspace_id = ".$GLOBALS['workspace_id']."
					";
		$result=$db->query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->Errors("Faield to delete. No rows affected.");
			return False;
		}
	}

	private function AddModules() {

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];
		$sql="SELECT module_id, name
					FROM ".$GLOBALS['database_prefix']."core_module_master
					WHERE available_teamspaces = 'y'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {

				$sql_ins="INSERT INTO ".$GLOBALS['database_prefix']."core_space_modules
									(workspace_id,teamspace_id,module_id)
									VALUES (
									".$GLOBALS['workspace_id'].",
									".$this->teamspace_id.",
									'".$row['module_id']."'
									)";
				$db->query($sql_ins);
				//echo $sql_ins."<br>";

				/* GET ALL THE TASKS IN THE MODULE FOLDER */
				$dir=$GLOBALS['dr']."modules/".$row['name']."/modules/";
				if (file_exists($dir)) {
					/* LOOP THE PHP FILES IN EACH MODULE */
					$dir_arr[]="";
					if ($handle = opendir($dir)) {
				    while (false !== ($file = readdir($handle))) {
					    if ($file != "." && $file != ".." && substr($file,-4)==".php") {
				  			$dir_arr[]=substr($file,0,-4);
				      }
				    }
				    closedir($handle);
					}
					/* SORT THE ARRAY INTO ALPHABETICAL ORDER */
					sort($dir_arr,SORT_REGULAR);
					/* CALL THE FUNCTION TO ADD */
					for ($i=1;$i<count($dir_arr);$i++) {
						//echo $dir." ok<br>";
						CheckCreateACLTaskExists($GLOBALS['wui']->GetColVal("role_id"),$row['module_id'],$dir_arr[$i],"","y",$this->teamspace_id);
					}
				}
				else {
					//echo $dir." 	Dir does not exist<br>";
				}
			}

		}
		else {
			//return $this->heirarchy_array;
		}
		return True;
	}

	private function AddUserRole() {

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_space_module_acl
					(workspace_id,teamspace_id,role_id)
					VALUES (
					'".$GLOBALS['workspace_id']."',
					'".$this->teamspace_id."',
					'".$GLOBALS['wui']->RoleID()."'
					)";
		//echo $sql."<br>";
		$result=$db->query($sql);
		if ($result) {
			return True;
		}
		else {
			return False;
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