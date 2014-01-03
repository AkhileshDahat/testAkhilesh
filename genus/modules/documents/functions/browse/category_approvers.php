<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr'].'include/functions/forms/transfer_select.php';

function CategoryApprovers($category_id) {

	/* DATABASE CONNECTION */
	$db=$GLOBALS['db'];
	//$app_db=$GLOBALS['app_db'];

	/* FIRST SQL IS TO DISPLAY ALL ROLES NOT USED IN THIS CATEGORY */
	$sql="SELECT wu.user_id, um.full_name
				FROM ".$GLOBALS['database_prefix']."core_space_users wu, ".$GLOBALS['database_prefix']."core_user_master um
				WHERE wu.workspace_id = ".$GLOBALS['ui']->WorkspaceID()."
				AND wu.user_id = um.user_id
				AND wu.user_id NOT IN (
					SELECT user_id
					FROM ".$GLOBALS['database_prefix']."document_categories_approvers
					WHERE category_id = ".$category_id."
				)
				ORDER BY um.full_name";
	$sql1="SELECT wu.user_id, um.full_name
				FROM ".$GLOBALS['database_prefix']."core_space_users wu, ".$GLOBALS['database_prefix']."core_user_master um
				WHERE wu.workspace_id = ".$GLOBALS['ui']->WorkspaceID()."
				AND wu.user_id = um.user_id
				AND wu.user_id IN (
					SELECT user_id
					FROM ".$GLOBALS['database_prefix']."document_categories_approvers
					WHERE category_id = ".$category_id."
				)
				ORDER BY um.full_name";
	//echo $sql1;
	$s="<table class='summary' cellpadding=0>\n";
	$s.="<form method='post' action='index.php?module=documents&task=category_approvers&category_id=".$_GET['category_id']."'>\n";
		$s.="<tr>\n";
			$s.="<td>\n";
			$s.="<table>\n";
				$s.="<tr>\n";
					$s.="<td width='48'><img src='images/nuvola/22x22/actions/encrypted.png'></td>\n";
					$s.="<td class='head'>Define which users must approve documents added to this category</td>\n";
				$s.="</tr>\n";
			$s.="</table>\n";
			$s.="</td>\n";
		$s.="</tr>\n";
			$s.="<tr class='font12'>\n";
				$s.="<td>\n";
				$s.=TransferSelect("user_id", "full_name", $sql, $sql1, "Select to add", "Select to remove",10);
				$s.="</td>\n";
		$s.="</tr>\n";
		$s.="<tr>\n";
			$s.="<td>";
			$s.="<input type='submit' name='submit' value='Apply Changes' class='buttonstyle'> | <a href='index.php?module=documents&task=list&parent_id=".$_GET['category_id']."'>Back</a></td>\n";
			$s.="</td>";
		$s.="</form>\n";
	$s.="</tr>\n";

	$s.="</table>\n";
	return $s;
}

?>