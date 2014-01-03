<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/hrms/functions/forms/add_user_form.php";
require_once $GLOBALS['dr']."modules/hrms/classes/user_id.php";
require_once $GLOBALS['dr']."modules/core/classes/workspace_users.php";

function LoadTask() {

	$c="";

	/* FORM PROCESSING */
	$show_form=True;

	/* THE FORM HAS BEEN SUBMITTED */
	if (ISSET($_POST['signup'])) {

		$db=$GLOBALS['db'];
		$db->Begin(); /* START THE TRANSACTION */

		$obj_ui=new UserID; /* NEW OBJECT FOR USERS */

		/* ADD NEW USER IF THERE'S NO USER ID TO EDIT */
		if (!ISSET($_POST['user_id']) || EMPTY($_POST['user_id'])) {

			/* ADD USER TO THE USER_MASTER TABLE */
			$result_add_um=$obj_ui->AddUser($_POST['role_id'],$_POST['login'],$_POST['password'],$_POST['title_id'],$_POST['full_name'],$_POST['timezone'],$_POST['country_id'],$_POST['identification_number']);
			//echo "Passed stage 1<br>";

			if ($result_add_um) {
				/* THE OBJECT FOR NEW WORKSPACE USERS */
				$obj_wu = new WorkspaceUsers();
				$obj_wu->SetParameters($GLOBALS['workspace_id'],$obj_ui->GetInfo('user_id'));
				$result_add_wu=$obj_wu->AddUser();

				/* DO A DOUBLE CHECK AS BOTH MUST SUCCEED */
				if ($result_add_wu) {
					$c.="Success";
					$db->Commit(); /* SAVE CHANGES */
					$show_form=False;
				}
				else {
					$db->Rollback(); /* FAILED FOR SOME REASON */
					$c.=$obj_wu->ShowErrors();
				}
			}
			else {
				$c.="Failed";
				$c.=$obj_ui->ShowErrors();
				$db->Rollback(); /* FAILED FOR SOME REASON */
			}
		}
		/* EDIT USERS BASED ON THE USER ID */
		elseif (ISSET($_POST['user_id']) && !EMPTY($_POST['user_id'])) {
			echo "editing";
			$obj_ui->SetParameters($_GET['user_id']);
			$result_edit=$obj_ui->EditUser($_POST['title_id'],$_POST['full_name'],$_POST['login'],$_POST['password'],$_POST['password_repeat'],$_POST['timezone'],$_POST['country_id'],$_POST['language']);
			/* PERFORM SOME CHECKING ON THE TRANSACTION */
			if ($result_edit) {
				$obj_wu = new WorkspaceUsers();
				$obj_wu->SetParameters($GLOBALS['workspace_id'],$obj_ui->GetInfo('user_id'));
				$result_edit_wu=$obj_wu->EditUser($_POST['role_id']);
				if ($result_edit_wu) {
					$db->Commit(); /* SAVE CHANGES */
					$c.="Success...<a href='index.php?module=hrms&task=user_list'>User List</a> | <a href='index.php?module=hrms&task=add_user&user_id=".$_GET['user_id']."'>View User</a>";
					$show_form=False;
				}
				else {
					$db->Rollback(); /* FAILED FOR SOME REASON */
					$c.="Failed";
					$c.=$obj_wu->ShowErrors();
				}
			}
			else {
				$db->Rollback(); /* FAILED FOR SOME REASON */
				$c.="Failed";
				$c.=$obj_ui->ShowErrors();
			}
		}

	}
	/* HERE WE'RE EDITNG THE USER SO WE NEED TO CREATE A NEW OBJECT TO RETRIEVE HIS VALUES */
	if (ISSET($_GET['user_id'])&& !EMPTY($_GET['user_id'])) {
		$user_id=$_GET['user_id'];
		//echo $user_id."<br>haha<br>";
		$edit_ui=new UserID; /* NEW OBJECT FOR USERS */
		$result=$edit_ui->SetParameters($user_id);
		if (!$result) {
			$c.="Error, invalid user id";
		}
		/* A NEW OBJECT FOR THE WORKSPACE USER INFORMATION */
		else {
			//echo $user_id."<br>haha<br>";
			$wui_edit=new WorkspaceUserInfo($user_id,$GLOBALS['workspace_id']);
			$role_id=$wui_edit->RoleID("role_id");

			/* THIS COMES FROM THE USER MASTER TABLE */
			$title_id=$edit_ui->GetInfo("title_id");
			$full_name=$edit_ui->GetInfo("full_name");
			$login=$edit_ui->GetInfo("login");
			$timezone=$edit_ui->GetInfo("timezone");
			$country_id=$edit_ui->GetInfo("country_id");
			$language=$edit_ui->GetInfo("language");
			$identification_number=$edit_ui->GetInfo("identification_number");

		}
	}
	else {
		$user_id="";
		$role_id="";
		$title_id="";
		$full_name="";
		$login="";
		$timezone="";
		$country_id="";
		$language="";
		$identification_number="";
		$user_id="";
	}

	if ($show_form) {
		if (ISSET($_GET['user_id'])) { $user_id=$_GET['user_id']; } else { $user_id=""; }
		$c.=AddUserForm($role_id,$title_id,$full_name,$login,$timezone,$country_id,$language,$identification_number,"index.php?module=hrms&task=add_user&user_id=".$user_id,"edit",$user_id);
	}

	return $c;
}
?>