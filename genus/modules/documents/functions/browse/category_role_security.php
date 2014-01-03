<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr'].'include/functions/forms/transfer_select.php';

function CategoryRoleSecurity($category_id) {

	/* DATABASE CONNECTION */
	$db=$GLOBALS['db'];
	//$app_db=$GLOBALS['app_db'];

	/* FIRST SQL IS TO DISPLAY ALL ROLES NOT USED IN THIS CATEGORY */
	$sql="SELECT crm.role_id, crm.role_name
				FROM ".$GLOBALS['database_prefix']."core_workspace_role_master crm
				WHERE crm.workspace_id = ".$GLOBALS['ui']->WorkspaceID()."
				AND role_id NOT IN (
					SELECT dcrs.role_id
					FROM ".$GLOBALS['database_prefix']."document_category_role_security dcrs
					WHERE dcrs.category_id = ".$category_id."
				)
				ORDER BY crm.role_name";
	$sql1="SELECT crm.role_id, crm.role_name
				FROM ".$GLOBALS['database_prefix']."core_workspace_role_master crm
				WHERE crm.workspace_id = ".$GLOBALS['ui']->WorkspaceID()."
				AND crm.role_id IN (
					SELECT dcrs.role_id
					FROM ".$GLOBALS['database_prefix']."document_category_role_security dcrs
					WHERE dcrs.category_id = ".$category_id."
				)
				ORDER BY crm.role_name";
	//echo $sql1;
	$s="<table class='plain_border' cellpadding=0>\n";
	$s.="<form method='post' action='index.php?module=documents&task=category_security&category_id=".$_GET['category_id']."'>\n";
		$s.="<tr>\n";
			$s.="<td>\n";
			$s.="<table>\n";
				$s.="<tr class='modulehead'>\n";
					$s.="<td width='48'><img src='images/nuvola/22x22/actions/encrypted.png'></td>\n";
					$s.="<td>Role category access</td>\n";
				$s.="</tr>\n";
			$s.="</table>\n";
			$s.="</td>\n";
		$s.="</tr>\n";
			$s.="<tr class='font12'>\n";
				$s.="<td>\n";
				$s.=TransferSelect("role_id", "role_name", $sql, $sql1, "Select to add", "Select to remove",10);
				$s.="</td>\n";
		$s.="</tr>\n";
		$s.="<tr>\n";
			$s.="<td>";
			$s.="<input type='submit' name='submit' value='Apply Role Changes' class='buttonstyle'> | <input class='buttonstyle' type=button value='Go Back' onCLick=\"document.location.href=index.php?module=documents&task=list&parent_id=".$_GET['category_id']."\"></td>\n";
			$s.="</td>";
		$s.="</form>\n";
	$s.="</tr>\n";

	$s.="</table>\n";

	$s.="<br><br><br>\n";
	/*
	*********************************************************************
		THIS NEXT SECTION DEALS WITH THE ADVANCED SECURITY SETTINGS
	**********************************************************************
	*/
	$s.="<table class='plain_border' cellpadding=0>\n";
		$s.="<tr class='modulehead'>\n";
			$s.="<td colspan='4'>Individual settings</td>\n";
		$s.="</tr>\n";
		$s.="<tr class='colhead'>\n";
			$s.="<td>Role</td>\n";
			$s.="<td align='center'>Browse</td>\n";
			$s.="<td align='center'>Upload</td>\n";
			$s.="<td align='center'>Delete</td>\n";
			$s.="<td align='center'>Full Control</td>\n";
		$s.="</tr>\n";

		$sql_detail="SELECT crm.role_id, crm.role_name, dcrs.browse, dcrs.upload, dcrs.delete_files
			FROM ".$GLOBALS['database_prefix']."core_workspace_role_master crm, ".$GLOBALS['database_prefix']."document_category_role_security dcrs
			WHERE crm.workspace_id = ".$GLOBALS['ui']->WorkspaceID()."
			AND crm.role_id IN (
				SELECT dcrs.role_id
				FROM ".$GLOBALS['database_prefix']."document_category_role_security dcrs
				WHERE dcrs.category_id = ".$category_id."
			)
			AND crm.role_id = dcrs.role_id
			AND dcrs.category_id = ".$category_id."
			ORDER BY crm.role_name";
		$result = $db->Query($sql_detail);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$s.="<tr>\n";
					$s.="<td>".$row['role_name']."</td>\n";
					if ($row['browse']=="y") { $checked="checked"; $val_save="n"; } else { $checked=""; $val_save="y"; }
					$s.="<td align='center'><input type='checkbox' ".$checked." name='browse' value='yes' onClick=\"document.location.href='index.php?module=documents&task=category_security&subtask=advanced_security&browse=".$val_save."&role_id=".$row['role_id']."&category_id=".$_GET['category_id']."'\"></td>\n";
					if ($row['upload']=="y") { $checked="checked"; $val_save="n"; } else { $checked=""; $val_save="y"; }
					$s.="<td align='center'><input type='checkbox' ".$checked." name='upload' value='yes' onClick=\"document.location.href='index.php?module=documents&task=category_security&subtask=advanced_security&upload=".$val_save."&role_id=".$row['role_id']."&category_id=".$_GET['category_id']."'\"></td>\n";
					if ($row['delete_files']=="y") { $checked="checked"; $val_save="n"; } else { $checked=""; $val_save="y"; }
					$s.="<td align='center'><input type='checkbox' ".$checked." name='delete' value='yes' onClick=\"document.location.href='index.php?module=documents&task=category_security&subtask=advanced_security&delete_files=".$val_save."&role_id=".$row['role_id']."&category_id=".$_GET['category_id']."'\"></td>\n";
					$s.="<td align='center'><input type='checkbox' name='full_control' value='yes'></td>\n";
				$s.="</tr>\n";
			}
		}
	$s.="</table>\n";
	return $s;
}

?>