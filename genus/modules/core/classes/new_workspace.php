<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/db/row_exists.php";
require_once $GLOBALS['dr']."include/functions/db/get_sequence_currval.php";
require_once $GLOBALS['dr']."include/functions/acl/check_create_acl_task_exists.php";

class NewWorkspace {

	public function AddWorkspace($workspace_code,$name,$description,$logo,$max_teamspaces,$max_size,$max_users,$date_start,
															 $date_end,$status_id,$enterprise,$user_id) {

		$db=$GLOBALS['db'];
		/*
		echo "debug<br>";
		echo $max_teamspaces." maxteamspaces<br>";
		echo $max_size;
		*/
		if (!IS_NUMERIC($max_teamspaces)) { $this->Errors("Max teamspaces must be a number"); return False; }
		if (!IS_NUMERIC($max_size)) { $this->Errors("Max size must be a number"); return False; }
		//if ($this->workspaceCodeExists($workspace_id,$teamspace_id,$workspace_code)) { $this->Errors("workspace Code Duplicate"); return False; }
		if (!RowExists("core_space_status_master","status_id",$status_id,"")) { $this->Errors("Invalid Status"); return False; }
		//if (!RowExists("workspace_status_master","status_id",$status_id,"AND workspace_id='".$workspace_id."' AND teamspace_id='".$teamspace_id."'")) { $this->Errors("Invalid Status"); return False; }
		//if (!RowExists("workspace_priority_master","priority_id",$priority_id,"AND workspace_id='".$workspace_id."' AND teamspace_id='".$teamspace_id."'")) { $this->Errors("Invalid Priority"); return False; }
		if ($enterprise=="y") { $enterprise="y"; } else { $enterprise="n"; }


		$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_workspace_master
					(workspace_code,name,logo,max_teamspaces,max_size,max_users,date_start,
					date_end,status_id,enterprise,user_id_added)
					VALUES (
					'".EscapeData($workspace_code)."',
					'".EscapeData($name)."',
					'".EscapeData($logo)."',
					".$max_teamspaces.",
					".$max_size.",
					".$max_users.",
					'".EscapeData($date_start)."',
					'".EscapeData($date_end)."',
					".$status_id.",
					'".$enterprise."',
					".$user_id."
					)";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			$this->last_created_workspace_id=$db->LastInsertID("s_workspace_id");
			if ($this->WorkspaceDefaultRole($this->last_created_workspace_id)) {
				if ($this->WorkspaceRoles($this->last_created_workspace_id)==True) {
					if ($this->WorkspaceUsers($user_id,$this->last_created_workspace_id,$this->workspace_administrator_role_id,"y")==True) {
						return True; /* SUCCESS */
					}
					else {
						return False;
					}
				}
				else {
					return False;
				}

			}
			else {
				return False;
			}
		}
		else {
			$this->Errors("Bug");
			return False;
		}
	}

	function GetNextWorkspaceID() {
		$db=$GLOBALS['db'];

		$sql="SELECT nextval('".$GLOBALS['database_prefix']."s_workspace_id') as workspace_id";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				return $row['workspace_id'];
			}
		}
		else {
			return False;
		}
	}

	function EditWorkspace($workspace_id,$workspace_code,$name,$description,$logo,$max_teamspaces,$max_size,$max_users,$date_start,
					$date_end,$status_id,$enterprise,$user_id) {

		$db=$GLOBALS['db'];

		if (EMPTY($workspace_id)) { $this->Errors("Invalid workspace");	return False; }

		if (EMPTY($workspace_code)) { $this->Errors("Invalid Code");	return False; }

		if (EMPTY($name)) { $this->Errors("Invalid name");	return False; }

		if (EMPTY($status_id)) { $this->Errors("Invalid Status");	return False; }
		if (EMPTY($user_id)) { $this->Errors("Invalid Credentials");	return False; }


		$sql="UPDATE ".$GLOBALS['database_prefix']."core_workspace_master
					SET workspace_code = '".$workspace_code."',
					name = '".$name."',
					description = '".$description."',
					logo = '".$logo."',
					max_teamspaces = '".$max_teamspaces."',
					max_size = '".$max_size."',
					max_users = '".$max_users."',
					date_start = '".$date_start."',
					date_end = '".$date_end."',
					status_id = '".$status_id."',
					enterprise = '".$enterprise."'
					WHERE workspace_id = '".$workspace_id."'
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

	function WorkspaceCodeExists($workspace_id,$teamspace_id,$workspace_code) {
		$db=$GLOBALS['db'];
		$sql="SELECT 'x'
				FROM ".$GLOBALS['database_prefix']."core_workspace_master
				WHERE workspace_id = '".$workspace_id."'
				AND teamspace_id = '".$teamspace_id."'
				AND workspace_code = '".$workspace_code."'
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

	function WorkspaceDefaultRole($workspace_id) {
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_workspace_role_master (workspace_id,role_name,default_role,create_teamspace)
					VALUES (
					'".$workspace_id."',
					'User',
					'y',
					'y'
					)";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->Errors("Could not create default role.");
			return False;
		}
	}

	public function WorkspaceUsers($user_id,$workspace_id,$workspace_role_id,$approved="n") {
		$db=$GLOBALS['db'];

		$sql="REPLACE INTO ".$GLOBALS['database_prefix']."core_space_users (user_id,workspace_id,role_id,approved)
					VALUES (
					'".$user_id."',
					'".$workspace_id."',
					'".$workspace_role_id."',
					'".$approved."'
					)";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->Errors("Could not assign default privileges.");
			return False;
		}
	}

	function WorkspaceRoles($workspace_id) {
		$db=$GLOBALS['db'];
		$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_workspace_role_master (workspace_id,role_name,default_role,create_teamspace,manage_workspaces)
					VALUES (
					'".$workspace_id."',
					'Administrator',
					'n',
					'y',
					'y'
					)";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			$this->workspace_administrator_role_id=$db->LastInsertId("s_workspace_role_id"); /* WE NEED TO STORE THIS FOR THE DEFAULT ROLES WHICH IS ADMINISTRATOR */
			/* ALLOW ALL ADMINISTRATORS TO ACCESS ALL MODULE TASKS */
			return True;
		}
		else {
			$this->Errors("Could not assign default user roles.");
			return False;
		}
	}

	function GetVar($v) {
		return $this->$v;
	}

	/*
		THIS FUNCTION INSTALLS THE MODULES
	*/
	public function WorkspaceModules($workspace_id,$user_id,$module_id="") {

		/* BASIC CHECKS SINCE THIS IS A PUBLIC FUNCTION */
		if (!IS_NUMERIC($workspace_id)) { $this->Errors("Invalid workspace ID"); return False; }
		if (!IS_NUMERIC($user_id)) { $this->Errors("Invalid user ID"); return False; }
		if (!EMPTY($module_id) && !IS_NUMERIC($module_id)) { $this->Errors("Invalid module ID"); return False; }

		/* IF A MODULE HAS BEEN SPECIFIED - FOR E.G IN THE INSTALL MODULE */
		if (!EMPTY($module_id)) {
			$module_id_sql="WHERE module_id = ".$module_id;
			$this->workspace_administrator_role_id=$GLOBALS['ui']->RoleID();
		}
		else {
			$module_id_sql="WHERE available_all_workspaces='y'";
		}

		/* QUERY */
		$db=$GLOBALS['db'];
		$sql="SELECT module_id
					FROM ".$GLOBALS['database_prefix']."core_module_master
					$module_id_sql
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_space_modules (workspace_id,module_id,datetime_installed)
							VALUES (
							".$workspace_id.",
							".$row['module_id'].",
							sysdate()
							)";
				//echo $sql."<br>";
				$result_ins=$db->Query($sql);
				if ($db->AffectedRows($result_ins) == 0) {
					$this->Errors("There was a problem adding your modules.");
					return False;
				}
				else {
					$result_wum=$this->WorkspaceUserModules($workspace_id,$row['module_id'],$user_id);
					if (!$result_wum) {
						$this->Errors("There was a problem adding user modules.");
						return False;
					}
					/*
					I AM REMOVING THIS FOR NOW. SICNE THIS IS A PUBLIC FUNCTION THE WORKSPACE_ADMINISTRATOR_ROLE_ID DOESNT EXIST
					$result_wmacl=$this->WorkspaceModuleACL($workspace_id,$row['module_id'],$this->workspace_administrator_role_id);
					if (!$result_wmacl) {
						$this->Errors("There was a problem adding user modules acl.");
						return False;
					}
					*/
				}
			}
		}
		return True; /* IF WE ENCOUNTER NO ERRORS */
	}

	/*
		THIS FUNCTION ENABLES THE MODULE FOR THE USER
		THIS IS SUPERCEEDED BY THE ACL OF COURSE
	*/
	public function WorkspaceUserModules($workspace_id,$module_id,$user_id) {
		$db=$GLOBALS['db'];
		$next_ordering=$this->WorkspaceUserNextOrdering($user_id,$workspace_id);
		$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_space_user_modules
					(user_id,workspace_id,module_id,ordering)
					VALUES (
					'".$user_id."',
					'".$workspace_id."',
					'".$module_id."',
					'".$next_ordering."'
					)";
		//echo $sql."<br>";
		/* I AM REMOVING THIS BECAUSE WE WANT TO ALLOW USERS TO CHOOSE THEIR MODULES NOT LET IT BE INSTALLED FOR THEM */
		//$result = $db->Query($sql);
		//if ($db->AffectedRows($result) == 0) {
			//return False;
		//}
		//else {
			/* INSERT INTO THE MODULE ACL */
			$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_space_module_acl
						(workspace_id,module_id,role_id)
						VALUES (
						'".$workspace_id."',
						'".$module_id."',
						'".$this->workspace_administrator_role_id."'
						)";
			//echo $sql."<br>";
			/* I AM REMOVING THIS BECAUSE THE ADMINISTRATOR ROLE IS NO LONGER REQUIRED. THERE'S A MENU TO CHOOSE WHICH ROLES CAN USE THE MODULE IN THE WS */
			//$result_acl = $db->Query($sql);
			//if ($db->AffectedRows($result_acl) > 0) {
				return True;
			//}
			//else {
				//return False;
			//}
		//}
	}

	/*
		THIS FUNCTION GRANTS ACCESS TO A MODULE TO A ROLE ID
		THE ROLE ID SHOULD BE DEFINED BEFOREHAND
	*/
	private function WorkspaceModuleACL($workspace_id,$module_id,$role_id) {
		$db=$GLOBALS['db'];
		$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_space_module_acl
					(workspace_id,module_id,role_id)
					VALUES (
					'".$workspace_id."',
					'".$module_id."',
					'".$role_id."'
					)";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) == 0) {
			return False;
		}
		else {
			return True;
		}
	}

	/* HERE WE SETUP ALL THE BASIC TASK ACL'S FOR EACH MODULE */
	public function WorkspaceTaskACL($workspace_id,$role_id,$access) {
		$db=$GLOBALS['db'];

		/* SELECT ALL THE MODULES BY NAME */
		$sql="SELECT mm.name, mm.module_id
					FROM ".$GLOBALS['database_prefix']."core_space_modules wm, ".$GLOBALS['database_prefix']."core_module_master mm
					WHERE wm.workspace_id = ".$workspace_id."
					AND wm.module_id = mm.module_id";
		//echo $sql_mod."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				//echo "ok";
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
						CheckCreateACLTaskExists($role_id,$row['module_id'],$dir_arr[$i],$workspace_id,$access);
					}
				}
				else {
					//echo $dir." 	Dir does not exist<br>";
				}
			}
		}
		else {
			echo "Zero rows found<br>";
		}
	}
	/* TEMP NOT USING THIS ONE BECAUSE IT ASSIGNS ACCESS TO ALL ROLES WHICH IS CRAZY */
	/* HERE WE SETUP ALL THE BASIC TASK ACL'S FOR EACH MODULE */
	public function WorkspaceTaskACLOld($workspace_id) {
		$db=$GLOBALS['db'];
		$sql="SELECT role_id
					FROM ".$GLOBALS['database_prefix']."core_role_master
					WHERE workspace_id = '".$workspace_id."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				/* SELECT ALL THE MODULES BY NAME */
				$sql_mod="SELECT mm.name
									FROM ".$GLOBALS['database_prefix']."core_space_modules wm, ".$GLOBALS['database_prefix']."core_module_master mm
									WHERE wm.workspace_id = ".$workspace_id."
									AND wm.module_id = mm.module_id";
				//echo $sql_mod."<br>";
				$result_mod = $db->Query($sql_mod);
				if ($db->NumRows($result_mod) > 0) {
					while($row_mod = $db->FetchArray($result_mod)) {
						/* GET ALL THE TASKS IN THE MODULE FOLDER */
						$dir=$GLOBALS['dr']."modules/".$row_mod['name']."/modules/";
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
								CheckCreateACLTaskExists($row['role_id'],$row_mod['name'],$dir_arr[$i]);
							}
						}
						else {
							//echo $dir." 	Dir does not exist<br>";
						}
					}
				}
			}
		}
	}

	private function WorkspaceUserNextOrdering($user_id,$workspace_id) {
		$db=$GLOBALS['db'];
		$sql="SELECT (MAX(ordering) + 1) as next_ordering
					FROM ".$GLOBALS['database_prefix']."core_space_user_modules
					WHERE user_id = '".$user_id."'
					AND workspace_id = '".$workspace_id."'
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

	function CountTotalWorkspaces($role_id) {
		$db=$GLOBALS['db'];
		$sql="SELECT count(*) AS total
					FROM ".$GLOBALS['database_prefix']."core_role_master crm
					WHERE crm.role_id = ".$role_id."
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				return $row['total'];
			}
		}
	}

	public function WorkspaceID() {
		return $this->last_created_workspace_id;
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}
}
?>