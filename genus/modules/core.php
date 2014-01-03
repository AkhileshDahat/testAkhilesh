<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* INCLUDE THE MODULE CONFIG FILE */
//require_once($GLOBALS['dr']."modules/core/config.php");

/* ACL */
require_once $GLOBALS['dr']."include/functions/acl/check_access.php";

function LoadModule($module_id,$anonymous_access) {

	//if (!ISSET($_SESSION['user_id']) && ISSET($_GET['task']) && $_GET['task'] != "login") {
		//return False;
	//}

	/* STORE THE GLOBAL MODULE ID WHICH WE ARE IN NOW */
	GLOBAL $module_id,$mainmenu,$module_name;
	$module_name=EscapeData($_GET['module']);
	$module_id=GetColumnValue("module_id","core_module_master","name",$module_name);

	$c="";


	/* CONTINUE IF WE HAVE A PERIOD ID IN THE USER SETTINGS TABLE */
	//$c="<table class='plain'>\n";
		//$c.="<tr>\n";

		if (ISSET($_GET['task']) && ($anonymous_access == "y" || CheckAccess($GLOBALS['wui']->RoleID(),$module_id,$_GET['task']))) {
			//$c.="<td width='90%' valign='top'>\n";
			$file_inc=$GLOBALS['dr']."modules/".$module_name."/modules/".$_GET['task'].".php";
			if (file_exists($file_inc)) {
				require_once($file_inc);
				if (function_exists("LoadTask")) {
					$c.=LoadTask();

					//$c.="<img src='".$GLOBALS['wb']."images/banners/banner_sms.jpg'><br />";
				}
			}
			//$c.="</td>\n";
		}
			else {
				//$c.="<td>Access Denied</td>";
		}
	//$c.="</table>\n";

	return $c;
}
?>