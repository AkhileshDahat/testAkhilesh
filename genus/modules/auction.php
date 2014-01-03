<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function LoadModule() {

	if (!ISSET($_SESSION['user_id'])) { return False; }
	//if (!ISSET($_GLOBALS['ui'])) { return False; }

	/* INCLUDE THE MODULE CONFIG FILE */
	require_once($GLOBALS['dr']."modules/auction/config.php");

	/* ACL */
	require_once $GLOBALS['dr']."include/functions/acl/check_access.php";

	/* MISC */
	require_once($GLOBALS['dr']."include/functions/design/module_menu_dynamic1.php");

	$c="";

	/* END PROCESSING */

	/* STORE THE GLOBAL MODULE ID WHICH WE ARE IN NOW */
	GLOBAL $module_id,$mainmenu,$module_name;
	$module_name=EscapeData($_GET['module']);
	$module_id=GetColumnValue("module_id","core_module_master","name",$module_name);

	/* CONTINUE IF WE HAVE A PERIOD ID IN THE USER SETTINGS TABLE */
	$c.="<table>\n";
		/* THIS IS THE MENU ON THE RIGHT */
		$c.="<tr>\n";
			/* THIS IS THE MENU ON THE RIGHT */
			$c.="<td valign='top' colspan=2>\n";
			$c.=ModuleMenuDynamic($module_id,$module_name,$GLOBALS['mainmenu'],"horizontal");
			$c.="</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			/* LOAD THE TASK */
			if (ISSET($_GET['task']) && CheckAccess($GLOBALS['wui']->RoleID(),$module_id,$_GET['task'])) {
				$c.="<td width='75%' valign='top'>\n";
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
		$c.="</tr>\n";
	$c.="</table>\n";

	return $c;
}
?>