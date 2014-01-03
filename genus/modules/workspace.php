<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/workspace/classes/workspace_id.php";
require_once $GLOBALS['dr']."modules/workspace/functions/misc/menu.php";

function LoadModule() {

	/* APPLY SECURITY SETTINGS FOR ANY WORKSPACE ID VIA GET OR POST */
	if (ISSET($_GET['workspace_id']) && IS_NUMERIC($_GET['workspace_id'])) {
		$wid=new WorkspaceID();
		$result_priv_check=$wid->SetParameters($_GET['workspace_id']);
		if (!$result_priv_check) {
			return "<div align='center'><h1>Access Denied</h1></div>";
		}
	}
	if (ISSET($_POST['workspace_id']) && IS_NUMERIC($_POST['workspace_id'])) {
		$wid=new WorkspaceID();
		$result_priv_check=$wid->SetParameters($_POST['workspace_id']);
		if (!$result_priv_check) {
			return "<div align='center'><h1>Access Denied</h1></div>";
		}
	}

	$c="<table>\n";
		$c.="<tr>\n";
			$c.="<td width='90%' valign='top'>\n";
			if (EMPTY($_GET['task'])) {
				require_once($GLOBALS['dr']."modules/workspace/modules/home.php");
			}
			else {
				$file_inc=$GLOBALS['dr']."modules/workspace/modules/".$_GET['task'].".php";
				if (file_exists($file_inc)) {
					require_once($file_inc);
					if (function_exists("LoadTask")) {

						$c.=LoadTask();
					}
				}
			}
			$c.="</td>\n";
			$c.="<td width='150' valign='top'>\n";
			$c.=Menu($_SESSION['user_id']);
			$c.="</td>\n";
		$c.="</tr>\n";
	$c.="</table>\n";

	return $c;
}
?>