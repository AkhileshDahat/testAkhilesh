<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."classes/form/form_validate.php";
require_once $GLOBALS['dr']."include/functions/forms/create_fields.php";
require_once $GLOBALS['dr']."include/functions/forms/dynamic_dropdown.php";

require_once $GLOBALS['dr']."modules/hrms/classes/user_id.php";
require_once $GLOBALS['dr']."modules/core/classes/new_workspace.php";

require_once $GLOBALS['dr']."modules/hrms/functions/defaults/role_id_default.php";
require_once $GLOBALS['dr']."modules/core/functions/defaults/default_workspace_signup_status_id.php";

function AddUserForm($role_id="",$title_id="",$full_name="",$email="",$timezone="",$country_id="",$language="",$identification_number="",$action="index.php?module=hrms&task=add_user",$mode="add",$user_id="") {
	$db=$GLOBALS['db'];
	$database_prefix=$GLOBALS['database_prefix'];
	$wb=$GLOBALS['wb'];

	$c="";

	$fv=new FieldValidation;
	$fv->OpenTag();
	/* PASSWORD DOES NOT NEED TO BE VERIFIED IF WE ARE ADDING */
	if ($mode=="add") {
		$fv->ValidateFields("title_id,full_name,login,password,password_repeat,timezone_id,country_id,language");
	}
	else {
		$fv->ValidateFields("title_id,full_name,login,timezone_id,country_id,timezone,country_id");
	}
	$fv->CloseTag();

	/* CHECK IF THE FORM HAS BEEN SUBMITTED */
	if (ISSET($_POST['signup']) && $mode=="add") {
		//echo "Adding user now";
		$db->Begin(); /* START THE TRANSACTION */
		$nu=new NewUser;
		$role_id_default=RoleIDDefault();


		$result_user=$nu->AddUser($role_id_default,$_POST['login'],$_POST['password'],
								 $_POST['full_name'],$_POST['timezone']);

		if (!$result_user) {
			/* CREATION OF USER FAILED */
			$db->Rollback();
			$c.=Alert(39,$nu->ShowErrors());
		}
		else {
			/* DEFAULT ROLE FOR WORKSPACE USERS */
			$v_workspace_role_id=GetColumnValue("role_id","core_role_master","workspace_id",$GLOBALS['ui']->WorkspaceID(),"AND default_role = 'y'");
			$result_ws=$nu->AddWorkspaceID($nu->UserID(),$GLOBALS['ui']->WorkspaceID(),$v_workspace_role_id);
			if (!$result_ws) {
				$db->Rollback();
			}
			else {
				$c.=Alert(40);
				$db->Commit();
			}
		}
	}
	else {
		if (ISSET($_POST['signup']) && $mode="edit") {
		}
	}

	/*
		DESIGN THE FORM
	*/
	$c.="<table class='plain'>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='tophead' colspan='4'><img src='".$wb."modules/hrms/images/home/logo48x48.png'>Workspace User Form</td>\n";
		$c.="</tr>\n";
		$c.="<form method='post' enctype='multipart/form-data' name='createticket' action='".$action."' onsubmit='return ValidateForm(this);'>\n";
		if (!EMPTY($user_id)) {
			$c.="<input type='hidden' name='user_id' value='".$user_id."'>\n";
		}
		$c.="<tr>\n";
			$c.="<td colspan='2'><br></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'>Personal Details</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'><hr></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Title*</td>\n";
			$c.="<td colspan='2'>".ShowDropDown("title_id","title_name","hrms_title_master","title_id",$title_id,"","","input_reqd")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Full Name*</td>\n";
			$c.="<td colspan='2'>".DrawInput("full_name","input_reqd",$full_name,"30","50")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'>Account</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'><hr></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Email*</td>\n";
			$c.="<td colspan='2'>".DrawInput("login","input_reqd",$email,"30","100")." (Login ID)</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Password</td>\n";
			$c.="<td colspan='2'>".DrawPassword("password","","15","100","input_reqd")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Repeat Password</td>\n";
			$c.="<td colspan='2'>".DrawPassword("password_repeat","","15","100","input_reqd")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Identification Number*</td>\n";
			$c.="<td colspan='2'>".DrawInput("identification_number","input_reqd",$identification_number,"30","100")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2'><br></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'>Workspace Information</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'><hr></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Role*</td>\n";
			$c.="<td colspan='2'>".ShowDropDown("role_id","role_name","core_workspace_role_master","role_id",$role_id,"","WHERE workspace_id = ".$GLOBALS['workspace_id'],"input_reqd")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2'><br></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'>Additional Information</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'><hr></td>\n";
		$c.="</tr>\n";
		/*
		$c.="<tr>\n";
			$c.="<td valign='top'>Photograph</td>\n";
			$c.="<td colspan='2'>".DrawFileUpload("photograph","input","","10","80")." (<10k jpg only)</td>\n";
		$c.="</tr>\n";
		*/
		$c.="<tr>\n";
			$c.="<td>Timezone*</td>\n";
			$c.="<td colspan='2'>".ShowDropDown("timezone","timezone","hrms_timezone_master","timezone",$timezone,"","","input_reqd")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2'><br></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'>Location</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'><hr></td>\n";
		$c.="</tr>\n";
		/*
		$c.="<tr>\n";
			$c.="<td valign='top'>Street Address</td>\n";
			$c.="<td colspan='2'>".DrawTextarea("street_address","inputstyle","",4,30)."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Postal Code</td>\n";
			$c.="<td colspan='2'>".DrawInput("postal_code","input","","30","100")."</td>\n";
		$c.="</tr>\n";
		*/
		$c.="<tr>\n";
			$c.="<td>Country*</td>\n";
			$c.="<td colspan='2'>".ShowDropDown("country_id","country_name","hrms_country_master","country_id",$country_id,"","")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Language*</td>\n";
			$c.="<td colspan='2'>".ShowDropDown("language","description","hrms_language_master","language",$language,"","")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2'><br></td>\n";
		$c.="</tr>\n";
		/*
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'>Workspace Information</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'><hr></td>\n";
		$c.="</tr>\n";

		$c.="<tr>\n";
			$c.="<td>Give your site a name</td>\n";
			$c.="<td colspan='2'>".DrawInput("workspace_code","input","","10","100")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Describe your site</td>\n";
			$c.="<td colspan='2'>".DrawInput("workspace_name","input","","30","100")."</td>\n";
		$c.="</tr>\n";
		*/
		$c.="<tr>\n";
			$c.="<td valign='top' class='head' colspan='4'>".DrawSubmit("submit","Save Details","buttonstyle","signup",True)."</td>\n";
		$c.="</tr>\n";
	$c.="</form>\n";
	$c.="</table>\n";
	return $c;
}
?>