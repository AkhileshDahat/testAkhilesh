<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function LoadModule() {

	/* ACL */
	require_once $GLOBALS['dr']."include/functions/acl/check_access.php";

	/* MISC */
	require_once($GLOBALS['dr']."include/functions/design/module_menu_dynamic.php");

	/* INCLUDE THE MODULE CONFIG FILE */
	require_once($GLOBALS['dr']."modules/sstars/config.php");

	/* INCLUDE THE USER CONFIGURATION SETTINGS FOR THE MODULE */
	require_once($GLOBALS['dr']."modules/sstars/classes/user_settings.php");
	$obj_us=new UserSettings($_SESSION['user_id']);

	/* THIS IS THE MENU FUNCTION */
	require_once $GLOBALS['dr']."modules/sstars/functions/menu.php";

	$c="";

	/* STORE THE GLOBAL MODULE ID WHICH WE ARE IN NOW */
	GLOBAL $module_id,$mainmenu,$module_name;
	$module_name=EscapeData($_GET['module']);
	$module_id=GetColumnValue("module_id","core_module_master","name",$module_name);

	/* THIS IS WHERE WE PROCESS ANY RIGHT HAND COLUMN ACTIONS. */
	if (ISSET($_GET['action'])) {
	}
	/* END PROCESSING */


	/* THIS IS THE SETUP FOR THE sstars MODULE */
	$c.="<table>\n";
		$c.="<tr>\n";
			/* LOAD THE TASK */
			if (ISSET($_GET['task']) && CheckAccess($GLOBALS['wui']->RoleID(),$module_id,$_GET['task'])) {
				$c.="<td width='90%' valign='top'>\n";
				$file_inc=$GLOBALS['dr']."modules/".$module_name."/modules/".$_GET['task'].".php";
				if (file_exists($file_inc)) {
					require_once($file_inc);
					if (function_exists("LoadTask")) {
						$c.=LoadTask();
					}
				}
				$c.="</td>\n";
			}
				else {
					$c.="<td>Access Denied</td>";
			}

			/* THIS IS THE MENU ON THE RIGHT AND THE UPLOAD BOX */
			/* THIS IS THE MENU ON THE RIGHT */
			$c.="<td width='150' valign='top'>\n";
			$c.=ModuleMenuDynamic($module_id,$module_name,$GLOBALS['mainmenu']);
			$c.="</td>\n";
		$c.="</tr>\n";
	$c.="</table>\n";

	return $c;
}
?>