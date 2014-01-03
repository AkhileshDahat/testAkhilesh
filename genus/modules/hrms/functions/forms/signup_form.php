<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."classes/form/form_validate.php";
require_once $GLOBALS['dr']."include/functions/forms/create_fields.php";
require_once $GLOBALS['dr']."include/functions/forms/dynamic_dropdown.php";

require_once $GLOBALS['dr']."modules/hrms/classes/new_user.php";
require_once $GLOBALS['dr']."modules/workspace/classes/new_workspace.php";

require_once $GLOBALS['dr']."modules/hrms/functions/defaults/role_id_default.php";
require_once $GLOBALS['dr']."modules/workspace/functions/defaults/default_workspace_signup_status_id.php";

function SignupForm() {
	$db=$GLOBALS['db'];
	$database_prefix=$GLOBALS['database_prefix'];
	$wb=$GLOBALS['wb'];

	$fv=new FieldValidation;
	$fv->OpenTag();
	$fv->ValidateFields("first_name,surname,login,password,password_repeat,timezone_id,workspace_name");
	$fv->CloseTag();

	/* CHECK IF THE FORM HAS BEEN SUBMITTED */

	if (ISSET($_POST['first_name'])) {
		$db->Begin(); /* START THE TRANSACTION */
		$nu=new NewUser;
		$role_id_default=RoleIDDefault();

		$result_user=$nu->AddUser($role_id_default,$_POST['login'],$_POST['password'],$_POST['title_id'],
								 $_POST['first_name'],$_POST['surname'],$_POST['timezone']);

		if (!$result_user) {
			/* CREATION OF USER FAILED */
			$db->Rollback();
			echo Alert(39,$nu->ShowErrors());
		}
		else {

			/* VARIABLES FROM THE FORM BELOW */
			$workspace_code=$_POST['workspace_code'];
			$workspace_name=$_POST['workspace_name'];

			/* SIGNUP VARIABLES FROM THE CONFIG FILE */
			$max_teamspaces=$GLOBALS['setup']->SignupMaxTeamspaces();
			$max_size=$GLOBALS['setup']->SignupMaxSize();
			$max_users=$GLOBALS['setup']->SignupMaxUsers();
			$enterprise=$GLOBALS['setup']->SignupEnterprise();
			$logo=$GLOBALS['setup']->SignupWorkspaceLogo();

			$available_start_date=Date("Y-m-d"); /* CURRENT DATE */
			$available_end_date=Date("Y-m-d",(time()+($GLOBALS['setup']->SignupExpiryDays()*86400))); /* EXPIRY DATE OF FREE SIGNUP ACCOUNTS */
			$status_id=DefaultWorkspaceSignupStatusID();
			$user_id=$nu->UserID(); /* GET THE USER ID FROM THE CLASS ABOVE */

			$nw=new NewWorkspace; /* INSTANTIATE THE WORKSPACE CLASS */
			$result_workspace=$nw->AddWorkspace($workspace_code,$workspace_name,$logo,$max_teamspaces,$max_size,
					$max_users,$available_start_date,$available_end_date,$status_id,$enterprise,$user_id);
			if (!$result_workspace) {
				/* CREATION OF WORKSPACE FAILED */
				$db->Rollback();
				echo Alert(41,$nw->ShowErrors());
			}
			else {
				/* SUCCESS */
				$workspace_id=$nw->WorkspaceID();
				$nw->WorkspaceModules($nw->WorkspaceID(),$user_id,$role_id_default);
				if ($nu->AdministrationRoleMaster($role_id_default,$workspace_id)) {
					$db->Commit();
					echo Alert(40);
				}
				else {
					echo Alert(42);
				}
			}
		}
	}

	/*
		DESIGN THE FORM
	*/
	$c="<table class='plain_border'>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='tophead' colspan='4'><img src='".$wb."modules/hrms/images/home/logo48x48.png'>Signup Form</td>\n";
		$c.="</tr>\n";
		$c.="<form method='post' enctype='multipart/form-data' name='createticket' action='index.php?module=signup' onsubmit='return ValidateForm(this);'>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2'><br></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'>Personal Details</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'><hr color='#669999'></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Title*</td>\n";
			$c.="<td colspan='2'>".ShowDropDown("title_id","title_name","hrms_title_master","title_id","","","","input_reqd")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>First Name*</td>\n";
			$c.="<td colspan='2'>".DrawInput("first_name","input_reqd","","30","50")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Surname*</td>\n";
			$c.="<td colspan='2'>".DrawInput("surname","input_reqd","","30","50")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Email*</td>\n";
			$c.="<td colspan='2'>".DrawInput("login","input_reqd","","30","100")." (This will be your login ID)</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Password*</td>\n";
			$c.="<td colspan='2'>".DrawPassword("password","","15","100","input_reqd")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Repeat Password*</td>\n";
			$c.="<td colspan='2'>".DrawPassword("password_repeat","","15","100","input_reqd")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2'><br></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'>Optional Information</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'><hr color='#669999'></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top'>Photograph</td>\n";
			$c.="<td colspan='2'>".DrawFileUpload("photograph","input","","10","80")." (Less than 10k in jpeg format only)</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Timezone</td>\n";
			$c.="<td colspan='2'>".ShowDropDown("timezone","timezone","hrms_timezone_master","timezone","","","","input_reqd")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2'><br></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'>Contact Information</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'><hr color='#669999'></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top'>Street Address</td>\n";
			$c.="<td colspan='2'>".DrawTextarea("street_address","inputstyle","",4,30)."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Postal Code</td>\n";
			$c.="<td colspan='2'>".DrawInput("postal_code","input","","30","100")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Country</td>\n";
			$c.="<td colspan='2'>".ShowDropDown("country_id","country_name","hrms_country_master","country_id","","","")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2'><br></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'>Workspace Information</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'><hr color='#669999'></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Give your site a name</td>\n";
			$c.="<td colspan='2'>".DrawInput("workspace_code","input","","10","100")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Describe your site</td>\n";
			$c.="<td colspan='2'>".DrawInput("workspace_name","input","","30","100")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='head' colspan='4'>".DrawSubmit("submit","Save Details","buttonstyle","signup",True)."</td>\n";
		$c.="</tr>\n";
	$c.="</form>\n";
	$c.="</table>\n";
	return $c;
}
?>