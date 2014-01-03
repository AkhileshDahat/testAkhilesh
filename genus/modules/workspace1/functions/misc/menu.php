<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."modules/workspace/functions/count/count_role_workspaces.php";

function Menu() {
	$ui=$GLOBALS['ui'];
	//$create_max_workspaces=$GLOBALS['ui']->CreateMaxWorkspaces();
	//$count_workspaces=CountUserWorkspaces($GLOBALS['ui']->RoleID());
	$c="<table class='plain' width='200'>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2' class='bold'>Workspace Menu</td>\n";
		$c.="</tr>\n";
		if ($ui->GetInfo("create_workspace") == "y") {
			$c.="<tr>\n";
				$c.="<td width='16'><img src='images/nuvola/16x16/actions/edit_add.png'></td>\n";
				$c.="<td><a href='index.php?module=workspace&task=add_workspace'>Add Workspace</a></td>\n";
			$c.="</tr>\n";
		}
		if ($ui->GetInfo("browse_workspaces") == "y") {
			$c.="<tr>\n";
				$c.="<td width='16'><img src='images/nuvola/16x16/actions/view_text.png'></td>\n";
				$c.="<td><a href='index.php?module=workspace&task=browse_workspaces'>Browse Workspaces</a></td>\n";
			$c.="</tr>\n";
		}
		if ($ui->GetInfo("browse_master_modules") == "y") {
			$c.="<tr>\n";
				$c.="<td width='16'><img src='images/nuvola/16x16/actions/view_text.png'></td>\n";
				$c.="<td><a href='index.php?module=workspace&task=master_modules'>Master Modules</a></td>\n";
			$c.="</tr>\n";
		}
		$c.="<tr>\n";
			$c.="<td colspan='2'><hr></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2' class='bold'>Account Information</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2'>Role: ".$ui->RoleName()."</td>\n";
		$c.="</tr>\n";
	$c.="</table>\n";

	return $c;
}