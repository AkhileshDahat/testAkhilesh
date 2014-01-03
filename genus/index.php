<?php
/* THIS ENSURES WE ARE ABLE TO CONTROL OUR INCLUDE FILES */
define( '_VALID_MVH', 1 );

/* SET THE TIMEZONE */
date_default_timezone_set('UTC');

/* set the error reporting level for this script */
//error_reporting(E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE);

require_once "include/functions/errors/error_handler.php";

/* set to the user defined error handler */
set_error_handler("ErrorHandler");

/* START OUTPUT BUFFERING AND SEND HEADERS */
ob_start();

/* DISABLE CACHING */
header("Pragma: no-cache");

/* CHECK FOR AN INSTALLATION FILE */
if (!file_exists("site_config.php")) { header("Location:install/"); }
if (file_exists("install/")) { die ("Please remove the install directory before continuing"); }

/**** WE SPLIT UP THE CONFIGURATION FILE HERE SO THAT WE CAN POST THE USERNAME AND PASSWORD FROM ANYWHERE ****/

/* GENERAL CONFIG */
require_once "config.php";

/* DATABASE CONFIGURATION */

require_once "db_config.php";
//$_SESSION['ex2'] = "SELECT workspace_code, date_start, concat('<a href=index.php?module=core&task=edit_workspace&subtask=del&workspace_id=',workspace_id,'>Modules</a>') as domodules, concat('<a href=index.php?module=core&task=edit_workspace&subtask=del&workspace_id=',workspace_id,'>Edit</a>') as doedit, concat('<a href=index.php?module=core&task=browse_workspaces&subtask=del&workspace_id=',workspace_id,'>Delete</a>') as dodelete FROM core_workspace_master ORDER BY workspace_id DESC";
//echo $_SESSION['ex2'];
/* THIS ALLOWS POSTING TO THE INDEX PAGE TO BE PICKED UP AND PROCESSED BEFORE WE DO CONFIG STUFF */
if (ISSET($_GET['dtask'])) {
	/**** LOGOUT ****/
	if ($_GET['dtask']=="logout" && ISSET($_SESSION['user_id'])) {
		require_once $dr."classes/login/logout.php";
		$logout=new Logout;
		$logout->SetCredentials($_SESSION['user_id']);
		$result=$logout->ExecuteLogout();
		if (!$result) { $errors->SetError("Error logging out."); }
	}
	/**** REMOVE LOGIN COOKIE ****/
	elseif ($_GET['dtask']=="remove_remember_me" && ISSET($_COOKIE['mvh_username'])) {
		setcookie("mvh_username",False);
	}
	/**** ACTIVATE WORKSPACE ****/
	elseif ($_GET['dtask']=="activate_workspace" && ISSET($_SESSION['user_id']) && ISSET($_GET['workspace_id'])) {
		require_once $dr."modules/core/classes/activate_workspace.php";
		$aw=new ActivateWorkspace;
		$aw->SetCredentials($_SESSION['user_id'],$_GET['workspace_id']);
		$result=$aw->Activate();
		if (!$result) { $errors->SetError($aw->ShowErrors()); }
	}
	/**** DEACTIVATE WORKSPACE ****/
	elseif ($_GET['dtask']=="deactivate_workspace" && ISSET($_SESSION['user_id'])) {
		require_once $dr."modules/core/classes/activate_workspace.php";
		$aw=new ActivateWorkspace;
		$result=$aw->Deactivate($_SESSION['user_id']);
		if (!$result) { $errors->SetError($aw->ShowErrors()); }
	}
	/**** ACTIVATE TEAMSPACE ****/
	elseif ($_GET['dtask']=="activate_teamspace" && ISSET($_SESSION['user_id']) && ISSET($_GET['teamspace_id'])) {
		require_once $dr."modules/teamspace/classes/activate_teamspace.php";
		$at=new ActivateTeamspace;
		$at->SetCredentials($_SESSION['user_id'],$_GET['teamspace_id']);
		$result=$at->Activate();
		if (!$result) { $errors->SetError($at->ShowErrors()); }
	}
	/**** DEACTIVATE TEAMSPACE ****/
	elseif ($_GET['dtask']=="deactivate_teamspace" && ISSET($_SESSION['user_id'])) {

		require_once $dr."modules/teamspace/classes/activate_teamspace.php";
		$at=new ActivateTeamspace;
		$result=$at->Deactivate($_SESSION['user_id']);
		if (!$result) { $errors->SetError($at->ShowErrors()); }
	}
	/**** DEACTIVATE TEAMSPACE ****/
	elseif ($_GET['dtask']=="show_dashboard" && ISSET($_SESSION['user_id'])) {
		require_once $dr."modules/hrms/classes/user_id.php";
		$obj_ui=new UserID;
		$obj_ui->SetParameters($_SESSION['user_id']);
		$result=$obj_ui->ChangeDashboard($_GET['do']);
		if (!$result) { $errors->SetError($obj_ui->ShowErrors()); }
	}
	/**** SET LANGUAGE ****/
	elseif ($_GET['dtask']=="language" && ISSET($_SESSION['user_id'])) {
		require_once $dr."modules/hrms/classes/user_id.php";
		//echo "ok";
		$obj_ui=new UserID;
		$result=$obj_ui->SetLanguage($_GET['lang']);
		if (!$result) { $errors->SetError($obj_ui->ShowErrors()); }
		header("location: index.php");
	}
}
/**** LOGIN MUST ALWAYS COME LAST ****/
if (ISSET($_POST['username']) && ISSET($_POST['password'])) {
	//echo "logging in now";
	if (ISSET($_POST['remember_me'])) { $remember_me="y"; } else { $remember_me="n"; }
	require_once $dr."modules/core/classes/login.php";
	//require_once $GLOBALS['dr']."classes/user/user_info.php";
	$login=new Login;
	$setparameter_result=$login->SetParameters($_POST['username'],$_POST['password'],$remember_me);
	if ($setparameter_result) {
		$result=$login->Verify();
		if (!$result) { $errors->SetError($login->ShowErrors()); }
		else {
			header("Location: index.php");
		}
	}
}

/* OTHER CLASSES AND COMMON INCLUDES */
require_once "common_config.php";

/* DESIGN PREFERENCES ETC */
require_once "preferences.php";

/********* CONTENT ALL GETS CALLED HERE ***********/
$head=new HTMLHead;
$head->IncludeFile("css");

$html_modu=ModuleCode();
/* CALL THE BODY AND FOOT FIRST */
$html_body=Body();
$html_foot=HTMLFoot();

/* SINCE THIS HANDLES DYNAMIC INCLUDES WE CALL IT LAST */
$html_head=$head->DrawHead();

echo $html_head;
echo $html_body;
echo $html_modu;
echo $html_foot;

/********* CONTENT ALL GETS CALLED HERE ***********/

/* THIS IS THE BODY FUNCTION */
function Body() {
	$dr=$GLOBALS['dr'];
	$wb=$GLOBALS['wb'];
	$errors=$GLOBALS['errors'];

	GLOBAL $mi; /* PUT THE MODULE ID IN THE GLOBAL CONTEXT */

	$c="<div id=\"content\">\n";

		$c.="<div id=\"top_info\">\n";
			$c.="<div id=\"logo\">\n";
				$c.="<a href='index.php'>".$GLOBALS['site_logo']."</a>\n";
			$c.="</div>\n";
			$c.="<div id=\"Logininfo\">\n";
				if (!ISSET($_SESSION['user_id'])) {
					$c.="<p><span id=\"loginbutton\"><a href=\"#\" title=\"Log In\">&nbsp;</a></span><br />\n";
					$c.="<b>You are not Logged in!</b> <a href=\"index.php?module=core&task=login\">Log in</a>";
					if ($GLOBALS['allow_registration']) {
						$c.=" | <a href='index.php?module=core&task=signup'>Register</a></p>\n";
					}
				}
				else {
					$c.="<p><span id=\"loginbutton\"><a href=\"#\" title=\"Log out\">&nbsp;</a></span><br />\n";
					$c.="<b>You are logged in as: ".$GLOBALS['ui']->GetInfo("full_name")."</b> <a href=\"index.php?dtask=logout\">Log out</a></p>\n";
				}
			$c.="</div>\n";
		$c.="</div>\n";






		if (ISSET($_SESSION['user_id'])) {
			$c.="<div id=\"topics\" name=\"topics\">\n";

				$c.="<a href=\"index.php\">Home</a> | \n";
				if ($GLOBALS['ui']->GetInfo("create_workspace") == "y") { $c.="<a href=\"index.php?module=core&task=add_workspace\">Add Workspace</a> | \n"; }
				if ($GLOBALS['ui']->GetInfo("manage_workspace") == "y") { $c.="<a href=\"index.php?module=core&task=browse_workspaces\">Configure Workspaces</a> | \n"; }


				if ($GLOBALS['ui']->GetInfo("manage_core_users") == "y") { $c.="<a href=\"index.php?module=core&task=browse_all_users\">Browse All Users</a> | \n"; }
				if ($GLOBALS['ui']->GetInfo("manage_core_users") == "y") { $c.="<a href=\"index.php?module=core&task=reset_user_password\">User Passwords</a> | \n"; }
				if ($GLOBALS['ui']->GetInfo("create_workspace_roles") == "y") { $c.="<a href=\"index.php?module=core&task=roles\">Configure Roles</a> | \n"; }
				if (IS_NUMERIC($GLOBALS['teamspace_id'])) {
					$c.="<a href='index.php?dtask=deactivate_teamspace'>Logout ".GetColumnValue("name","core_teamspace_master","teamspace_id",$GLOBALS['ui']->TeamspaceID())."</a> | ";
				}
				if (!EMPTY($GLOBALS['workspace_id'])) {
					$c.="<a href='index.php?dtask=deactivate_workspace'>"._LOGOUT_WORKSPACE_."</a>\n";
				}


				//$c.="<div class=\"thirds\">\n";
					$obj_wi=$GLOBALS['obj_wi'];
					$ui=$GLOBALS['ui'];
					$workspace_id=$ui->WorkspaceID();
					if ($obj_wi->GetColVal("enterprise")=="y") {
						$c.="<ul>\n";
						if ($GLOBALS['ui']->GetInfo("show_dashboard")=="y") {
							$c.="<li><a href='index.php?dtask=show_dashboard&do=n'>Classic</a>";
						}
						else {
							$c.="<li><a href='index.php?dtask=show_dashboard&do=y'>Dashboard</a>";
						}
						$c.="<li><a href='index.php?module=workspace&task=browse_workspaces'>Workspace Settings</a>";
					}
				//$c.="</div>\n";
			$c.="</div>\n";
		}

		if ($errors->ErrorCount() > 0) {
			$c.="<div id=errorblock>\n";
			$c.=$errors->GetErrors();
			$c.="</div>\n";
		}
		if ($errors->AlertCount() > 0) {
			$c.="<div id=alertblock>\n";
			$c.=$errors->GetAlerts();
			$c.="</div>\n";
		}
		else {
			//$c.="<div id=errorblock>no errors</div>";
		}

		// NOT LOGGED IN - SHOW THE LOGIN FORM
		if (!ISSET($_SESSION['user_id']) && !ISSET($_GET['module'])) {
			require_once $dr."modules/core/functions/forms/login_form.php";
			$c.=LoginForm();
		}
		/* LOGGED IN & NO MODULE IN QUERYSTRING & NO MODULE SELECTED*/
		elseif (ISSET($_SESSION['user_id']) && !ISSET($_GET['module']) && ISSET($GLOBALS['ui']) && ISSET($GLOBALS['wui'])) {
			/* PROCESS THE ACTIVATION AND DEACTIVATION OF MODULES IN THE WORKSPACE HERE AS WE NEED UI TO BE SET */
			if (ISSET($_GET['wtask']) && $_GET['wtask']=="install_workspace_user_module") {
				require_once $dr."modules/core/classes/add_remove_user_workspace_module.php";
				$aruwm=new AddRemoveUserWorkspaceModule;
				$aruwm->SetParameters($_GET['module_id']);
				$result=$aruwm->AddRemove();
				//if (!$result) { echo Alert("3",$at->ShowErrors()); }
			}
			/* ACTIVATE THE TEAMSPACE MODULES */
			if (ISSET($_GET['wtask']) && $_GET['wtask']=="install_teamspace_user_module") {
				require_once $dr."modules/teamspace/classes/add_remove_user_teamspace_module.php";
				$arutm=new AddRemoveUserTeamspaceModule;
				$arutm->SetParameters($_GET['module_id']);
				$result=$arutm->AddRemove();
				//if (!$result) { echo Alert("3",$at->ShowErrors()); }
			}
			/* INCLUDE THE MAIN FILES FOR THE WORKSPACE */
			require_once($dr."modules/core/functions/browse/non_enterprise_workspace_modules.php");
			require_once($dr."include/functions/design/teamspace_slider.php");
			require_once($dr."include/functions/teamspace/user_teamspaces.php"); /* TODO: CHANGE THIS TO THE WORKSPACE FOLDER */
			//require_once($dr."modules/teamspace/functions/browse/user_available_modules.php");
			require_once($dr."modules/teamspace/classes/user_available_modules.php"); //

			$c.="<div id=mainbody>\n";
			$c.="<table>\n";
				/* THIS IS THE TRADITIONAL ICON DASHBOARD FOR NON ENTERPRISE ACCOUNTS */
				$show_dashboard=false;

				if ($GLOBALS['ui']->GetInfo("show_dashboard")=="y" && !EMPTY($GLOBALS['workspace_id']) && $GLOBALS['obj_wi']->GetColVal("enterprise")=="y") {
					$show_dashboard=true;
				}

				if (!$show_dashboard) {
					$c.="<td colspan='3'>\n";
					$c.="<table cellspacing='0'>\n";
						$c.="<tr>\n";
							if (IS_NUMERIC($GLOBALS['teamspace_id'])) {
								$obj_uam=new UserAvailableModules;
								$c.="<td width='150' valign='top'>".$obj_uam->ShowUserAvailableModules()."</td>\n";
							}
							else {
								$obj_uam=new UserAvailableModules;
								if ($obj_uam->CountUserAvailableModules() > 0) {
									$c.="<td width='150' valign='top'>".TeamspaceSliderItems()."</td>\n";
								}
							}
							$c.="<td width='630'>".NonEnterpriseModules($GLOBALS['ui']->WorkspaceID(),$_SESSION['user_id'],$GLOBALS['wui']->RoleID(),True)."</td>\n";
							if (!IS_NUMERIC($GLOBALS['teamspace_id'])) {
								$c.="<td width='150' valign='top'>".UserTeamspaces()."</td>\n";
							}
						$c.="</tr>\n";
					$c.="</table>\n";
					$c.="</td>\n";
				}
				else {
					/* THIS IS THE ENTERPRISE DASHBOARD */
					require_once($dr."modules/core/functions/browse/enterprise_workspace_modules.php");
					require_once($dr."classes/enterprise/left_bar.php");
					$obj_lb=new LeftBar;

					/* ALL THE DASHBOARD VIEW FUNCTIONS */
					require_once($dr."modules/simpledoc/functions/browse/dashboard_simpledoc_view.php");
					require_once($dr."modules/announcements/functions/browse/dashboard_announcements_view.php");
					require_once($dr."modules/projects/functions/browse/dashboard_show_projects.php");
					$c.="<td colspan='3'>\n";
					$c.="<table cellspacing='10' width=100% border=0>\n";
						$c.="<tr>\n";
							$c.="<td width=20% valign='top'>".$obj_lb->Draw()."".EnterpriseWorkspaceModules()."</td>\n";
							$c.="<td width=40% valign='top'>".DashboardSimpleDocView()."</td>\n";
							$c.="<td width=40% valign='top'>";
							$c.=DashboardAnnouncementsView();
							$c.=DashboardShowProjects();
							$c.="</td>\n";
							$c.="</tr>\n";
					$c.="</table>\n";
					$c.="</td>\n";
				}
			$c.="</tr>\n";
			$c.="</table>\n";
			$c.="</div>\n";
		}
		/* LOGGED IN, NO MODULE, NO WORKSPACE*/
		elseif (ISSET($_SESSION['user_id']) && !ISSET($_GET['module']) && EMPTY($workspace_id)) {
			require_once $dr."modules/core/functions/browse/select_workspace.php";
			$c.="<table cellspacing='0' class='plain'>\n";
				$c.="<tr>\n";
					$c.="<td width=90% valign='top'>".SelectWorkspace()."</td>\n";
				$c.="</tr>\n";
			$c.="</table>\n";
		}



	return $c;
}


function ModuleCode() {
	$dr=$GLOBALS['dr'];
	$wb=$GLOBALS['wb'];
	$errors=$GLOBALS['errors'];
	$c="";
	/* WITH A MODULE e.g SIGNUP */
	if (ISSET($_GET['module']) && file_exists($dr."modules/".EscapeData($_GET['module']).".php")) {

		$module_id=GetColumnValue("module_id","core_module_master","name",$_GET['module']);
		require_once $dr."modules/".EscapeData($_GET['module']).".php";
		require_once $dr."classes/modules/module_id.php";

		$mi=new ModuleID();
		$module_result=$mi->Info($module_id);
		$anonymous_access=$mi->GetInfo("anonymous_access");

		$c.="<div id='mainbody'>\n";
			/* CHECK FOR ERRORS OR ACCESS DENIED */
			if ($module_result && $mi->CheckACL()) {
				$c.=LoadModule($module_id,$anonymous_access);
			}
			else {
				$c.="<div id=accessdenied>Access to module denied.</div>";
			}
		$c.="</div>\n";
	}
	return $c;
}

/* HTML FOOTER FUNCTION */
function HTMLFoot() {
	$c="";
	$c.="<div id=\"footer\">\n";
	if ($GLOBALS['pref_show_home_footer']) {
		$c.="| ";
		$footer_arr=array(_POLICY_,"About","Help");
		foreach ($footer_arr as $a) {
			$c.="<a href='index.php?module=core&task=".strtolower($a)."'>$a</a> | ";
		}
		if (ISSET($_SESSION['user_id'])) {
			$c.="<a href='index.php?dtask=language&lang=en'>English</a> | ";
			//$c.="<a href='index.php?dtask=language&lang=af'>Afrikaans</a>";
		}
	}
	else {
		$c.="<br />";
	}
	$c.="</div>\n";
	$c.="</div>\n"; // closing main body div
	$c.="</body>\n";
	$c.="</html>\n";
	return $c;
}
?>