<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/db/row_exists.php";
//require_once $dr."include/functions/db/get_col_value.php";
require_once $GLOBALS['dr']."modules/teamspace/functions/log/teamspace_history.php";

class AddRemoveUserTeamspaceModule {

	public function SetParameters($module_id) {

		if (!IS_NUMERIC($module_id)) { $this->Errors("Invalid module id"); return False; }

		$this->teamspace_id=$GLOBALS['ui']->teamspace_id;
		$this->module_id=$module_id;

		$this->user_teamspace_module_exists=RowExists("core_space_user_modules","teamspace_id",$this->teamspace_id,"AND module_id = '".$module_id."' AND user_id = ".$_SESSION['user_id']."");
		$this->module_name=GetColumnValue("name","core_module_master","module_id",$this->module_id,"");
	}
	public function AddRemove() {
		if ($this->user_teamspace_module_exists) {
			$this->DeleteUserTeamspaceModule();
		}
		else {
			$this->AddUserTeamspaceModule();
		}
	}

	private function DeleteUserTeamspaceModule() {

		$db=$GLOBALS['db'];

		/* CHECK THAT THE TEAMSPACE HAS THE MODULE */
		if (!$this->user_teamspace_module_exists) { $this->Error("Module does not exist"); return False; }

		/* DELETE THE MODULE */
		$sql="DELETE FROM ".$GLOBALS['database_prefix']."core_space_user_modules
					WHERE teamspace_id = '".$this->teamspace_id."'
					AND module_id = '".$this->module_id."'
					AND user_id = ".$_SESSION['user_id']."
					";
		$result=$db->Query($sql);

		/* LOG THE CHANGES */
		$log_desc=$this->module_name." deleted personal teamspace";

		if ($db->AffectedRows($result) > 0) {
			if (TeamspaceHistory($this->teamspace_id,$log_desc,$_SESSION['user_id'])) {
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

	private function AddUserTeamspaceModule() {

		$db=$GLOBALS['db'];

		$next_ordering=$this->TeamspaceUserNextOrdering();

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_space_user_modules (user_id,teamspace_id,module_id,ordering)
					VALUES (
					".$_SESSION['user_id'].",
					'".$this->teamspace_id."',
					'".$this->module_id."',
					".$next_ordering."
					)
					";
		$result = $db->Query($sql);
		$log_desc=$this->module_name." added to personal teamspace";
		if ($db->AffectedRows($result) > 0) {
			if (TeamspaceHistory($this->teamspace_id,$log_desc,$_SESSION['user_id'])) {
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

	private function TeamspaceUserNextOrdering() {
		$db=$GLOBALS['db'];
		$sql="SELECT (MAX(ordering) + 1) as next_ordering
					FROM ".$GLOBALS['database_prefix']."core_space_user_modules
					WHERE user_id = '".$_SESSION['user_id']."'
					AND teamspace_id = '".$GLOBALS['teamspace_id']."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				if (EMPTY($row['next_ordering'])) {
					return "1";
				}
				else {
					return $row['next_ordering'];
				}
			}
		}
		else {
			return "1";
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