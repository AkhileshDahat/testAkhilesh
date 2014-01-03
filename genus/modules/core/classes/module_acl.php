<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/db/row_exists.php";
require_once $GLOBALS['dr']."include/functions/acl/check_create_acl_task_exists.php";

require_once $GLOBALS['dr']."modules/core/functions/log/workspace_history.php";

class ModuleACL {

	public function __construct() {
		$this->err = "";
	}

	public function SetParameters($workspace_id,$module_id,$role_id) {

		if (!IS_NUMERIC($workspace_id)) { $this->Error("Invalid Workspace"); return False; }
		if (!IS_NUMERIC($module_id)) { $this->Error("Invalid Module"); return False; }
		if (!IS_NUMERIC($role_id)) { $this->Error("Invalid Role"); return False; }

		$this->role_exists=RowExists("core_space_module_acl","workspace_id",$workspace_id,"AND module_id = '".$module_id."' AND role_id = ".$role_id."");
		$this->module_name=STRTOLOWER(GetColumnValue("name","core_module_master","module_id",$module_id,""));

		$this->workspace_id=$workspace_id;
		$this->module_id=$module_id;
		$this->role_id=$role_id;

	}

	function AddRemoveRole() {
		if (!$this->role_exists) {
			return $this->AddWorkspaceModuleRole();
		}
		else {
			return $this->DeleteWorkspaceModuleRole();
		}
	}

	public function AddWorkspaceModuleRole() {
		$db=$GLOBALS['db'];
		$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_space_module_acl (workspace_id,module_id,role_id)
					VALUES (
					".$this->workspace_id.",
					'".$this->module_id."',
					".$this->role_id."
					)
					";
		//echo $sql."<br>";
		$result=$db->Query($sql);
		$log_desc=$this->module_name." role added to workspace";
		if ($db->AffectedRows($result) > 0) {
			if (WorkspaceHistory($this->workspace_id,$log_desc,$GLOBALS['user_id'])) {
				/* GIVE ALL ACCESS TO THE MENU ITEMS FOR THE MODULE TO THIS ROLE */
				$this->AddWorkspaceTaskACL();
				return True;
			}
			else {
				$this->Error("Logging the change failed. Transaction aborted. Please submit a bug report");
				return False;
			}
		}
		else {
			return False;
		}
	}

	public function DeleteWorkspaceModuleRole() {

		$db=$GLOBALS['db'];

		/* DELETE THE MODULE */
		$sql="DELETE FROM ".$GLOBALS['database_prefix']."core_space_module_acl
					WHERE workspace_id = '".$this->workspace_id."'
					AND module_id = '".$this->module_id."'
					AND role_id = '".$this->role_id."'
					";
		echo $sql."<br>";
		$result=$db->Query($sql);

		/* LOG THE CHANGES */
		$log_desc=$this->module_name." role deleted from workspace";

		if ($db->AffectedRows($result) > 0) {
			if (WorkspaceHistory($this->workspace_id,$log_desc,$GLOBALS['user_id'])) {
				return True;
			}
			else {
				$this->Error("Logging the change failed. Transaction aborted. Please submit a bug report");
				return False;
			}
		}
		else {
			return False;
		}
	}
	/*
		THIS FUNCTION LOOPS THROUGH ALL THE MODULES (FILESYSTEM) IN ORDER TO GRANT ACCESS TO ALL THE MENU ITEMS
		IT CALLS ANOTHER FUCNTION TO INSERT - check_create_acl_task_exists
	*/
	private function AddWorkspaceTaskACL() {

		$db=$GLOBALS['db'];

		/* SETUP THE INITIAL MODULE DIRECTORY */
		$dir=$GLOBALS['dr']."modules/".$this->module_name."/modules/";

		/* CHECK THAT THE MODULE EXISTS */
		if (!file_exists($dir)) { $this->Error("No such directory"); return False; }

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

		/* LOOP THROUGH ALL THE FILES IN THE MODULE TASK */
		for ($i=1;$i<count($dir_arr);$i++) {
			/* CALL THE FUNCTION TO CHECK AND INSERT */
			CheckCreateACLTaskExists($this->role_id,$this->module_id,$dir_arr[$i],$this->workspace_id,"y");
		}

	}

	private function Error($v) {
		$this->err.=$v;
	}

	public function ShowErrors() {
		return $this->err;
	}
}
?>