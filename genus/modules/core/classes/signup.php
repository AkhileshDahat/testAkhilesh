<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/db/get_column_value.php";

require_once $GLOBALS['dr']."modules/hrms/classes/user_id.php";
require_once $GLOBALS['dr']."modules/core/classes/new_workspace.php";

require_once $GLOBALS['dr']."modules/hrms/functions/defaults/role_id_default.php";
require_once $GLOBALS['dr']."modules/core/functions/defaults/default_workspace_signup_status_id.php";

class Signup {

	function __construct() {
	}

	public function Add() {
		/* ALLOW REGISTRATION */
		if (!$GLOBALS['allow_registration']) { $this->Errors("Signup is disabled"); return False; }

		/* CHECKS FOR SIGNUP - THE REST ARE HANDLED BY OTHER CLASSES */
		if (ISSET($_SESSION['gd_string']) && $this->security_code != $_SESSION['gd_string']) { $this->Errors("Invalid security code, please re-enter"); return False; }

		$OUserID=new UserID;
		$role_id_default=RoleIDDefault();
		$result_user=$OUserID->AddUser($role_id_default,$this->login,$this->password,$this->title_id,$this->full_name,$this->timezone,$this->country_id,$this->identification_number,"n",True);
		if (!$result_user) {
			$this->Errors($OUserID->ShowErrors());
			return False;
		}
		else {
			/* SIGNUP VARIABLES FROM THE CONFIG FILE */
			$max_teamspaces=$GLOBALS['setup']->SignupMaxTeamspaces();
			$max_size=$GLOBALS['setup']->SignupMaxSize();
			$max_users=$GLOBALS['setup']->SignupMaxUsers();
			$enterprise=$GLOBALS['setup']->SignupEnterprise();
			$logo=$GLOBALS['setup']->SignupWorkspaceLogo();

			$available_start_date=Date("Y-m-d"); /* CURRENT DATE */
			$available_end_date=Date("Y-m-d",(time()+($GLOBALS['setup']->SignupExpiryDays()*86400))); /* EXPIRY DATE OF FREE SIGNUP ACCOUNTS */
			$status_id=DefaultWorkspaceSignupStatusID();
			$user_id=$OUserID->GetInfo("user_id"); /* GET THE USER ID FROM THE CLASS ABOVE */

			$nw=new NewWorkspace; /* INSTANTIATE THE WORKSPACE CLASS */
			$result_workspace=$nw->AddWorkspace($this->workspace_code,"","",$logo,$max_teamspaces,$max_size,
					$max_users,$available_start_date,$available_end_date,$status_id,$enterprise,$user_id);
			if (!$result_workspace) {
				/* CREATION OF WORKSPACE FAILED */
				//echo "Failed to create workspace";
				$this->Errors($nw->ShowErrors());
				return False;
			}
			else {
				/* SUCCESS */
				$workspace_id=$nw->WorkspaceID();
				$nw->WorkspaceModules($nw->WorkspaceID(),$user_id);
				$workspace_administrator_role_id=$nw->GetVar("workspace_administrator_role_id");
				$nw->WorkspaceTaskACL($workspace_id,$workspace_administrator_role_id,"y");
				return True;
			}
		}
	}

	public function Join() {

		/* CHECKS FOR SIGNUP - THE REST ARE HANDLED BY OTHER CLASSES */
		//if ($this->security_code != $_SESSION['gd_string']) { $this->Errors("Invalid security code, please re-enter"); return False; }

		$OUserID=new UserID;
		$role_id_default=RoleIDDefault();
		$result_user=$OUserID->AddUser($role_id_default,$this->login,$this->password,$this->title_id,$this->full_name,$this->timezone,
																	$this->country_id,$this->identification_number);
		if (!$result_user) {
			$this->Errors($OUserID->ShowErrors());
			return False;
		}
		else {

			/* GET THE USER ID FROM THE CLASS ABOVE */
			$user_id=$OUserID->GetInfo("user_id");

			/* INSTANTIATE THE WORKSPACE CLASS */
			$nw=new NewWorkspace;

			/* GET THE VALUES FROM THE WORKSPACE */
			$workspace_id=GetColumnValue("workspace_id","core_workspace_master","workspace_code",$this->workspace_code);
			$role_id=GetColumnValue("role_id","core_workspace_role_master","workspace_id",$workspace_id,"AND default_role = 'y'");

			$result_join=$nw->WorkspaceUsers($user_id,$workspace_id,$role_id);
			if ($result_join) {
				return True;
			}
			else {
				$this->Errors($nw->ShowErrors());
				return False;
			}
		}
	}

	public function GetPostFields() {
		/* THE ARRAY OF POSTED FIELDS */
		$req_fields=array("login","password","title_id","full_name","timezone","country_id","identification_number","workspace_code","security_code");

		for ($i=0;$i<count($req_fields);$i++) {

			//echo $_POST['application_id'];
			if (ISSET($_POST[$req_fields[$i]]) && !EMPTY($_POST[$req_fields[$i]])) {
				$this->SetVariable($req_fields[$i],$_POST[$req_fields[$i]]);
			}
			else {
				//echo "<br>".$this->req_fields[$i]."<br>";
				$this->$req_fields[$i]="";
			}
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