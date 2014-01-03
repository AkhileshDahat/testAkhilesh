<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* INCLUDE THE MODULE CONFIG FILE */
require_once($GLOBALS['dr']."modules/crm/config.php");

/* ACL */
require_once $GLOBALS['dr']."include/functions/acl/check_access.php";

/* MISC */
require_once($GLOBALS['dr']."include/functions/design/module_menu_dynamic.php");

function LoadModule() {

	$c="";

	/* THIS IS WHERE WE PROCESS ANY RIGHT HAND COLUMN ACTIONS. */

	if (ISSET($_GET['action'])) {
	}

	/* END PROCESSING */

	/* STORE THE GLOBAL MODULE ID WHICH WE ARE IN NOW */
	GLOBAL $module_id,$mainmenu,$module_name;
	$module_name=EscapeData($_GET['module']);
	$module_id=GetColumnValue("module_id","core_module_master","name",$module_name);


	$c.="<table>\n";
		$c.="<tr>\n";
			if (ISSET($_GET['task']) && CheckAccess($GLOBALS['wui']->RoleID(),$module_id,$_GET['task'])) {
				$c.="<td width='90%' valign='top'>\n";
				$file_inc=$GLOBALS['dr']."modules/crm/modules/".$_GET['task'].".php";
				if (file_exists($file_inc)) {
					require_once($file_inc);
					if (function_exists("LoadTask")) {
						$c.=CurveBox(LoadTask());
					}
				}
				$c.="</td>\n";
			}
				else {
					$c.="<td>Access Denied</td>";
			}
			/* THIS IS THE MENU ON THE RIGHT AND THE UPLOAD BOX	*/
			$c.="<td width='150' valign='top'>\n";
			//$c.=ModuleMenu("CRM Menu","crm",Array("home","add_account","accounts","add_contact","contacts","acl"),array("nuovext/16x16/actions/home.png","nuovext/16x16/actions/home.png","nuovext/16x16/actions/home.png","nuovext/16x16/actions/edit_add.png","nuovext/16x16/actions/edit_add.png","nuovext/16x16/actions/edit_add.png"));
			$c.=ModuleMenuDynamic($module_id,$module_name,$GLOBALS['mainmenu']);
			$c.="</td>\n";
		$c.="</tr>\n";
	$c.="</table>\n";

	return $c;
}
?>