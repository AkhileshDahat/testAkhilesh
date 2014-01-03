<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/db/row_exists.php";
//require_once $dr."include/functions/db/get_col_value.php";
//require_once $GLOBALS['dr']."modules/workspace/functions/log/workspace_history.php";

class AddRemoveModule {

	function __construct() {
		$this->err="";
	}

	function SetParameters($workspace_id,$module_id) {

		$this->workspace_id=$workspace_id;
		$this->module_id=$module_id;

		$this->module_exists=RowExists("core_space_modules","workspace_id",$workspace_id,"AND module_id = '".$module_id."'");
		$this->module_name=GetColumnValue("name","core_module_master","module_id",$module_id,"");

	}

	function DeleteWorkspaceModule() {

		$db=$GLOBALS['db'];

		/* CHECK THAT THE WORKSPACE HAS THE MODULE */
		if (!$this->module_exists) { $this->Error("Module does not exist"); return False; }

		/* DELETE THE MODULE */
		$sql="DELETE FROM ".$GLOBALS['database_prefix']."core_space_modules
					WHERE workspace_id = '".$this->workspace_id."'
					AND module_id = '".$this->module_id."'
					";
		$result=$db->Query($sql);

		/* DELETE THE WORKSPACE FROM ANY USER ACCOUNTS */
		$sql="DELETE FROM ".$GLOBALS['database_prefix']."core_space_user_modules
					WHERE workspace_id = '".$this->workspace_id."'
					AND module_id = '".$this->module_id."'
					";
		$result=$db->Query($sql);

		/* DELETE THE WORKSPACE FROM THE WORKSPACE ACL */
		$sql="DELETE FROM ".$GLOBALS['database_prefix']."core_space_module_acl
					WHERE workspace_id = '".$this->workspace_id."'
					AND module_id = '".$this->module_id."'
					";
		$result=$db->Query($sql);

		/* LOG THE CHANGES */
		$log_desc=$this->module_name." deleted from workspace";

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

	function AddWorkspaceModule() {
		$db=$GLOBALS['db'];
		$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_space_modules (workspace_id,module_id)
					VALUES (
					'".$workspace_id."',
					'".$module_id."'
					)
					";
		$log_desc=$this->module_name." added to workspace";
		if ($db->AffectedRows($result) > 0) {
			if (WorkspaceHistory($workspace_id,$log_desc,$GLOBALS['user_id'])) {
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

	function Error($v) {
		$this->err.=$v;
	}

	function ShowErrors() {
		return $this->err;
	}
}
?>