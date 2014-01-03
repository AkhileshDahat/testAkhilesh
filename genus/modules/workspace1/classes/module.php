<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."include/functions/db/row_exists.php";
//require_once $dr."include/functions/db/get_col_value.php";
require_once $dr."modules/workspace/functions/log/workspace_history.php";

class Module {

	function SetParameters($workspace_id,$module_id) {

		$this->module_exists=RowExists("core_space_modules","workspace_id",$workspace_id,"AND module_id = '".$module_id."'");
		$this->module_name=GetColumnValue("name","core_module_master","module_id",$module_id,"");
		$this->workspace_id=$workspace_id;
		$this->module_id=$module_id;
	}

	function AddRemoveModule() {
		if (!$this->module_exists) {
			return $this->AddWorkspaceModule();
		}
		else {
			return $this->DeleteWorkspaceModule();
		}
	}

	function DeleteWorkspaceModule() {

		$db=$GLOBALS['db'];

		/* DELETE THE MODULE */
		$sql="DELETE FROM ".$GLOBALS['database_prefix']."core_space_modules
					WHERE workspace_id = '".$this->workspace_id."'
					AND module_id = '".$this->module_id."'
					";
		//cho $sql."<br>";
		$result=$db->Query($sql);

		/* LOG THE CHANGES */
		$log_desc=$this->module_name." deleted from workspace";

		if ($db->AffectedRows($result) > 0) {
			$this->DeleteWorkspaceUserModules();
			$this->DeleteTeamspaceModules();
			$this->DeleteUserTeamspaceModules();
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

	function DeleteWorkspaceUserModules() {
		$db=$GLOBALS['db'];
		$sql="DELETE FROM ".$GLOBALS['database_prefix']."core_space_user_modules
					WHERE workspace_id = '".$this->workspace_id."'
					AND module_id = '".$this->module_id."'
					";
		$result=$db->Query($sql);
		/* NO AFFECTED ROWS AS THERE MIGHT BE NO USERS WITH THIS MODULE */

	}

	function DeleteTeamspaceModules() {
		$db=$GLOBALS['db'];
		$sql="DELETE tmd
					FROM ".$GLOBALS['database_prefix']."core_teamspace_master AS tm, ".$GLOBALS['database_prefix']."core_space_modules AS tmd
					WHERE tm.workspace_id = '".$this->workspace_id."'
					AND tm.teamspace_id = tmd.teamspace_id
					AND tmd.module_id = '".$this->module_id."'
					";
		$result=$db->Query($sql);
		/* NO AFFECTED ROWS AS THERE MIGHT BE NO USERS WITH THIS MODULE */
	}

	function DeleteUserTeamspaceModules() {
		$db=$GLOBALS['db'];
		$sql="DELETE tum
					FROM ".$GLOBALS['database_prefix']."core_teamspace_master AS tm, ".$GLOBALS['database_prefix']."core_space_modules AS tum
					WHERE tm.workspace_id = '".$this->workspace_id."'
					AND tm.teamspace_id = tum.teamspace_id
					AND tum.module_id = '".$this->module_id."'
					";
		$result=$db->Query($sql);
		/* NO AFFECTED ROWS AS THERE MIGHT BE NO USERS WITH THIS MODULE */
	}

	function AddWorkspaceModule() {
		$db=$GLOBALS['db'];
		$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_space_modules (workspace_id,module_id)
					VALUES (
					'".$this->workspace_id."',
					'".$this->module_id."'
					)
					";
		$result=$db->Query($sql);
		$log_desc=$this->module_name." added to workspace";
		if ($db->AffectedRows($result) > 0) {
			if ($this->AddUserWorkspceModule()) {
				if (WorkspaceHistory($this->workspace_id,$log_desc,$GLOBALS['user_id'])) {
					return True;
				}
				else {
					$this->Error("Logging the change failed. Transaction aborted. Please submit a bug report");
					return False;
				}
			}
		}
		else {
			return False;
		}
	}

	function AddUserWorkspceModule() {
		$db=$GLOBALS['db'];
		$success=True;
		$sql="SELECT user_id
					FROM ".$GLOBALS['database_prefix']."core_space_users
					WHERE workspace_id = '".$this->workspace_id."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);

		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_space_user_modules
							(user_id,workspace_id,module_id)
							VALUES (
							'".$row['user_id']."',
							'".$this->workspace_id."',
							'".$this->module_id."'
							)";
				$result_ins=$db->Query($sql);
				$log_desc=$this->module_name." added to workspace";
				if ($db->AffectedRows($result_ins) == 0) {
					$this->Error("Failed to grant access to one of the users. Please submit a bug report");
					$success=False;
				}
			}
		}
		return $success;
	}

	function Error($v) {
		$this->err.=$v;
	}

	function ShowErrors() {
		return $this->err;
	}
}
?>