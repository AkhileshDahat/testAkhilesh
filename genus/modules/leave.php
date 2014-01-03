<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* ACL */
require_once $GLOBALS['dr']."include/functions/acl/check_access.php";

/* INCLUDE THE MODULE CONFIG FILE */
require_once($GLOBALS['dr']."modules/leave/config.php");

/* MISC */
require_once($GLOBALS['dr']."include/functions/design/module_menu_dynamic.php");

function LoadModule() {

	if (!ISSET($_SESSION['user_id'])) { return False; }

	$c="";

	/* THIS IS WHERE WE PROCESS ANY RIGHT HAND COLUMN ACTIONS. */

	if (ISSET($_GET['action'])) {
		if ($_GET['action']=="set_period") {
			require_once($GLOBALS['dr']."modules/leave/classes/user_settings.php");
			$GLOBALS['obj_us']->Edit($_GET['period_id']);
			$GLOBALS['obj_us']->Refresh();
		}
	}

	/* END PROCESSING */

	/* STORE THE GLOBAL MODULE ID WHICH WE ARE IN NOW */
	GLOBAL $module_id,$mainmenu,$module_name;
	$module_name=EscapeData($_GET['module']);
	$module_id=GetColumnValue("module_id","core_module_master","name",$module_name);

	/* CONTINUE IF WE HAVE A PERIOD ID IN THE USER SETTINGS TABLE */
	$c.="<table>\n";
		$c.="<tr class='alternatecell1'>\n";
			$c.="<td colspan='2' align='right'>\n";
			$c.="Current Period: ".$GLOBALS['obj_us']->GetInfo("date_from")." to ".$GLOBALS['obj_us']->GetInfo("date_to")." | <a href='index.php?module=leave&task=set_period'>Change</a>";
			$c.="</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			/* HERE WE CHECK FOR A PERIOD IN THE USER_SETTINGS TABLE */
			if (($_GET['task'] != "periods" && $_GET['task'] != "acl" && !IS_NUMERIC($GLOBALS['obj_us']->GetInfo("period_id"))) || $_GET['task']=="set_period") {
				require_once($GLOBALS['dr']."modules/leave/functions/browse/set_period.php");
				$c.="<td width='80%' valign='top'>\n";
				$c.=SetPeriod();
				$c.="</td>\n";
			}
			else {
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
			}
			/* THIS IS THE MENU ON THE RIGHT */
			$c.="<td width='250' valign='top'>\n";
			$c.=ModuleMenuDynamic($module_id,$module_name,$GLOBALS['mainmenu'],"");
			$c.="</td>\n";
		$c.="</tr>\n";
	$c.="</table>\n";

	return $c;
}
?>