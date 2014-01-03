<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class MasterModules {

	public function Info($module_id) {
		$db=$GLOBALS['db'];
		if (!IS_NUMERIC($module_id)) { $this->Errors("Non numeric module ID"); return False; }

		$sql="SELECT name,description,available_teamspaces,logo,signup_module
					FROM ".$GLOBALS['database_prefix']."core_module_master
					WHERE module_id = ".$module_id."
					";
		//echo $sql;
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$this->name=$row['name'];
				$this->description=$row['description'];
				$this->available_teamspaces=$row['available_teamspaces'];
				$this->logo=$row['logo'];
				$this->signup_module=$row['signup_module'];
			}
		}
	}

	public function GetInfo($v) {
		return $this->$v;
	}

	public function Add($name,$description,$available_teamspaces,$logo,$signup_module) {
		$db=$GLOBALS['db'];
		if (EMPTY($name)) { $this->Errors("Module Name Required"); return False; }
		if (EMPTY($description)) { $this->Errors("Description required"); return False; }
		if (EMPTY($logo)) { $this->Errors("Logo Required"); return False; }
		if (EMPTY($available_teamspaces)) { $available_teamspaces="False"; } else { $available_teamspaces="True"; }
		if (EMPTY($signup_module)) { $signup_module="False"; } else { $signup_module="True"; }

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_module_master
					(name,description,available_teamspaces,logo,signup_module)
					VALUES (
					'".EscapeData($name)."',
					'".EscapeData($description)."',
					'".$available_teamspaces."',
					".EscapeData($logo).",
					'".$signup_module."'
					)";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->Errors("Bug in creating module.");
			return False;
		}
	}

	public function Edit($module_id,$name,$description,$available_teamspaces,$logo,$signup_module) {

		$db=$GLOBALS['db'];
		if (!IS_NUMERIC($module_id)) { $this->Errors("Non numeric module ID"); return False; }
		if (EMPTY($name)) { $this->Errors("Module Name Required"); return False; }
		if (EMPTY($description)) { $this->Errors("Description required"); return False; }
		if ($available_teamspaces=="y") { $available_teamspaces="y"; } else { $available_teamspaces="n"; }
		if (EMPTY($logo)) { $this->Errors("Logo Required"); return False; }
		if ($signup_module=="y") { $signup_module="y"; } else { $signup_module="n"; }

		$sql="UPDATE ".$GLOBALS['database_prefix']."core_module_master SET
					name = '".EscapeData($name)."',
					description = '".EscapeData($description)."',
					available_teamspaces = '".$available_teamspaces."',
					logo = '".EscapeData($logo)."',
					signup_module = '".$signup_module."'
					WHERE module_id = $module_id";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->Errors("Bug in editing error.");
			return False;
		}
	}

	public function Delete($user_id,$error_id) {

		if (EMPTY($user_id)) { $this->Errors("Invalid User"); return False; }
		if (EMPTY($error_id)) { $this->Errors("Invalid Error"); return False; }
		if (!IS_NUMERIC($error_id)) { $this->Errors("Non Numeric Error ID"); return False; }

		$this->db=$GLOBALS['db'];

		$sql="DELETE FROM ".$GLOBALS['database_prefix']."core_error_messages
					WHERE error_id = '".EscapeData($error_id)."'
					";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->Errors("Error not deleted.");
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