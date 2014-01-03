<?php
/* SET THE ERROR MESSAGES TO MAX */
error_reporting(E_ALL);

/* START OUTPUT BUFFERING AND SEND HEADERS */
ob_start();
header("Pragma: no-cache");

/* THIS ENSURES WE ARE ABLE TO CONTROL OUR INCLUDE FILES */
define('_VALID_MVH',1);
define('_VALID_MVH_MOBILE_',1);

/**** WE SPLIT UP THE CONFIGURATION FILE HERE SO THAT WE CAN POST THE USERNAME AND PASSWORD FROM ANYWHERE ****/

/* GENERAL CONFIG */
require_once "../config.php";

/* DATABASE CONFIGURATION */
require_once "../db_config.php";

/* MOBILE ALERTS */
require_once $dr."classes/mobile/mobile_alert.php";

if (ISSET($_GET['dtask'])) {
	/**** LOGOUT ****/
	if ($_GET['dtask']=="logout" && !EMPTY($_SESSION['user_id'])) {
		require_once $dr."classes/login/logout.php";
		$logout=new Logout;
		$logout->SetCredentials($_SESSION['user_id']);
		$result=$logout->ExecuteLogout();
		if (!$result) { echo Alert("2"); }
	}
	/**** ACTIVATE WORKSPACE ****/
	elseif ($_GET['dtask']=="activate_workspace" && ISSET($_SESSION['user_id']) && ISSET($_GET['workspace_id'])) {
		require_once $dr."modules/workspace/classes/activate_workspace.php";
		$aw=new ActivateWorkspace;
		$aw->SetCredentials($_SESSION['user_id'],$_GET['workspace_id']);
		$result=$aw->Activate();
		if (!$result) { echo Alert("3",$aw->ShowErrors()); }
	}
	/**** ACTIVATE WORKSPACE ****/
	elseif ($_GET['dtask']=="deactivate_workspace" && ISSET($_SESSION['user_id'])) {
		require_once $dr."modules/workspace/classes/activate_workspace.php";
		$aw=new ActivateWorkspace;
		$result=$aw->Deactivate($_SESSION['user_id']);
		if (!$result) { echo Alert("3",$aw->ShowErrors()); }
	}
}
/**** LOGIN MUST ALWAYS COME LAST ****/
if (ISSET($_POST['username']) && ISSET($_POST['password'])) {
	if (ISSET($_POST['remember_me'])) { $remember_me="y"; } else { $remember_me="n"; }
	require_once $dr."modules/core/classes/login.php";
	//require_once $GLOBALS['dr']."classes/user/user_info.php";
	$login=new Login;
	$setparameter_result=$login->SetParameters($_POST['username'],$_POST['password'],$remember_me);
	if ($setparameter_result) {
		$result=$login->Verify();
		if (!$result) { echo Alert("2"); }
		header("Location: index.php");
	}
}

/* OTHER CLASSES AND COMMON INCLUDES */
require_once "../common_config.php";

/********* CONTENT ALL GETS CALLED HERE ***********/
$head=new HTMLHead;
$head->IncludeFile("mobilecss");

/* CALL THE BODY AND FOOT FIRST */
$html_body=Body();
$html_foot=HTMLFoot();

/* SINCE THIS HANDLES DYNAMIC INCLUDES WE CALL IT LAST */
$html_head=$head->DrawHead();

echo $html_head;
echo $html_body;
echo $html_foot;
/********* CONTENT ALL GETS CALLED HERE ***********/

/* THIS IS THE BODY FUNCTION */
function Body() {
	$dr=$GLOBALS['dr'];
	$wb=$GLOBALS['wb'];
	GLOBAL $mi; /* PUT THE MODULE ID IN THE GLOBAL CONTEXT */
	$c="<table align='left' class='plain'>\n";
		$c.="<tr>\n";
			$c.="<td><a href='index.php'><img src='".$GLOBALS['wb']."images/home/my_virtual_hut_logo_mobile.gif' border='0'></a></td>\n";
		$c.="</tr>\n";
		/*
		$c.="<tr>\n";
			$c.="<td bgcolor='#99CCFF'>\n";
			if (ISSET($_SESSION['user_id'])) {
				require_once $dr."include/functions/design/login_bar.php";
				$c.=LoginBar(True);
			}
			else {
				require_once $dr."include/functions/design/logout_bar.php";
				$c.=LogoutBar();
			}
			$c.="</td>\n";
		$c.="</tr>\n";
		*/
		/* NOT LOGGED IN AND NO MODULE IN QUERYSTRING */
		if (!ISSET($_SESSION['user_id']) && !ISSET($_GET['module'])) {
			require_once $dr."modules/core/functions/forms/login_form.php";
			$c.="<tr>\n";
				$c.="<td>".LoginForm()."</td>\n";
			$c.="</tr>\n";
		}
		/* LOGGED IN & NO MODULE IN QUERYSTRING & NO WORKSPACE SELECTED*/
		elseif (ISSET($_SESSION['user_id']) && !ISSET($_GET['module']) && ISSET($GLOBALS['ui']) && ISSET($GLOBALS['wui'])) {
			require_once $dr."modules/workspace/functions/browse/non_enterprise_workspace_modules.php";
			require_once $dr."include/functions/teamspace/user_teamspaces.php";
			$c.="<tr>\n";
				$c.="<td>\n";
				//$c.=NonEnterpriseWorkspaceModules($GLOBALS['ui']->WorkspaceID(),$_SESSION['user_id'],$GLOBALS['wui']->RoleID(),True);
				$c.=NonEnterpriseModules($GLOBALS['ui']->WorkspaceID(),$_SESSION['user_id'],$GLOBALS['wui']->RoleID(),True);
				$c.="</td>\n";
				//$c.="<td width='150' valign='top'>\n";
				//$c.=UserTeamspaces($GLOBALS['ui']->WorkspaceID(),$_SESSION['user_id']);
				//$c.="</td>\n";
			$c.="</tr>\n";
		}
		elseif (ISSET($_SESSION['user_id']) && !ISSET($_GET['module']) && EMPTY($workspace_id)) {
			require_once $dr."modules/workspace/functions/browse/select_workspace.php";
			$c.="<tr>\n";
				$c.="<td>".SelectWorkspace()."</td>\n";
			$c.="</tr>\n";
			//$c.=CurveBox(SelectWorkspace());
		}
		/* EITHER LOGGED IN OR NOT WITH A MODULE */
		elseif (ISSET($_GET['module']) && file_exists($dr."modules/".$_GET['module'].".php")) {
			$module_id=GetColumnValue("module_id","core_module_master","name",$_GET['module']);
			require_once $dr."modules/".$_GET['module'].".php";
			require_once $dr."classes/modules/module_id.php";
			$mi=new ModuleID();
			$mi->Info($module_id);
			$c.="<tr>\n";
				$c.="<td>".LoadModule($module_id)."</td>\n";
			$c.="</tr>\n";
		}
		$c.="<tr>\n";
			$c.="<td bgcolor='#99CCFF' align='center'>&copy;2004-".date("Y")."</td>\n";

		$c.="</tr>\n";
	$c.="</table>\n";
	return $c;
}

/* HTML FOOTER FUNCTION */
function HTMLFoot() {
	$c="</body>\n";
	$c.="</html>\n";
	return $c;
}
?>