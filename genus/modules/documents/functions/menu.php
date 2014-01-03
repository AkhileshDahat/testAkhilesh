<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function Menu($user_id,$category_id) {

	$wb=$GLOBALS['wb'];
	$wui=$GLOBALS['wui'];
	$module_id=$GLOBALS['module_id'];

	$c="<table id='rightmenu'>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2' class='date'>Documents Menu</td>\n";
		$c.="</tr>\n";
		if (CheckAccess($wui->RoleID(),$module_id,"pending_approval")) {
			$c.="<tr>\n";
				$c.="<td width='16'><img src='".$wb."images/nuvola/16x16/actions/view_text.png'></td>\n";
				$c.="<td><a href='index.php?module=documents&task=home&category_id=".$category_id."'>"._DOCUMENTS_MENU_LIST_."</a></td>\n";
			$c.="</tr>\n";
		}
		if (CheckAccess($wui->RoleID(),$module_id,"pending_approval")) {
			$c.="<tr>\n";
				$c.="<td width='16'><img src='".$wb."images/nuvola/16x16/actions/edit_add.png'></td>\n";
				$c.="<td><a href='index.php?module=documents&task=add_category&category_id=".$category_id."'>"._DOCUMENTS_MENU_ADD_FOLDER_."</a></td>\n";
			$c.="</tr>\n";
		}
		if (!defined( '_VALID_MVH_MOBILE_' )) {
			if ($category_id > 0) {
				$c.="<tr>\n";
					$c.="<td width='16'><img src='".$wb."modules/documents/images/default/menu_bookmark_category.png'></td>\n";
					$c.="<td><a href='index.php?module=documents&task=home&action=bookmark&category_id=".$category_id."'>"._DOCUMENTS_MENU_BOOKMARK_FOLDER_."</a></td>\n";
				$c.="</tr>\n";
			}
			if ($category_id > 0 && CheckAccess($wui->RoleID(),$module_id,"category_security")) {
				$c.="<tr>\n";
					$c.="<td width='16'><img src='".$wb."modules/documents/images/default/menu_category_security.png'></td>\n";
					$c.="<td><a href='index.php?module=documents&task=category_security&category_id=".$category_id."'>"._DOCUMENTS_MENU_CATEGORY_SECURITY_."</a></td>\n";
				$c.="</tr>\n";
			}
			if ($category_id > 0 && CheckAccess($wui->RoleID(),$module_id,"category_approvers")) {
				$c.="<tr>\n";
					$c.="<td width='16'><img src='".$wb."modules/documents/images/default/menu_category_approvers.png'></td>\n";
					$c.="<td><a href='index.php?module=documents&task=category_approvers&category_id=".$category_id."'>"._DOCUMENTS_MENU_CATEGORY_APPROVERS_."</a></td>\n";
				$c.="</tr>\n";
			}
		}
		if (CheckAccess($wui->RoleID(),$module_id,"pending_approval")) {
			$c.="<tr>\n";
				$c.="<td width='16'><img src='".$wb."images/nuvola/16x16/actions/edit_add.png'></td>\n";
				$c.="<td><a href='index.php?module=documents&task=pending_approval'>"._DOCUMENTS_MENU_PENDING_APPROVAL_."</a></td>\n";
			$c.="</tr>\n";
		}
		if (CheckAccess($wui->RoleID(),$module_id,"my_bookmarks")) {
			$c.="<tr>\n";
				$c.="<td width='16'><img src='".$wb."modules/documents/images/default/menu_my_bookmarks.png'></td>\n";
				$c.="<td><a href='index.php?module=documents&task=my_bookmarks'>"._DOCUMENTS_MENU_MY_BOOKMARKS_."</a></td>\n";
			$c.="</tr>\n";
		}
		if (CheckAccess($wui->RoleID(),$module_id,"reports")) {
			$c.="<tr>\n";
				$c.="<td width='16'><img src='".$wb."modules/documents/images/default/menu_reports.png'></td>\n";
				$c.="<td><a href='index.php?module=documents&task=reports'>"._DOCUMENTS_MENU_REPORTS_."</a></td>\n";
			$c.="</tr>\n";
		}
		if (CheckAccess($wui->RoleID(),$module_id,"settings")) {
			$c.="<tr>\n";
				$c.="<td width='16'><img src='".$wb."modules/documents/images/default/menu_settings.png'></td>\n";
				$c.="<td><a href='index.php?module=documents&task=settings'>"._DOCUMENTS_MENU_SETTINGS_."</a></td>\n";
			$c.="</tr>\n";
		}
		if ($GLOBALS['dus']->GetInfo("show_rad_upload")=="y") { $change_icon="disable_upload"; $change_to="n"; $change_show=_DOCUMENTS_MENU_DISABLE_UPLOAD_; } else { $change_icon="enable_upload"; $change_to="y"; $change_show=_DOCUMENTS_MENU_ENABLE_UPLOAD_; }
		$c.="<tr>\n";
			$c.="<td width='16'><img src='".$wb."modules/documents/images/default/menu_".$change_icon.".png'></td>\n";
			$c.="<td><a href='index.php?module=documents&task=home&category_id=".$category_id."&action=show_rad_upload&enable=".$change_to."'>".$change_show."</a></td>\n";
		$c.="</tr>\n";
		if ($category_id > 0 && $GLOBALS['dus']->GetInfo("total_cut_documents") > 0) {
			$c.="<tr>\n";
				$c.="<td width='16'><img src='".$wb."images/nuvola/16x16/actions/bookmarks_list_add.png'></td>\n";
				$c.="<td><a href='index.php?module=documents&task=home&action=paste_documents&category_id=".$category_id."'>"._DOCUMENTS_MENU_PASTE_DOCUMENTS_."</a></td>\n";
			$c.="</tr>\n";
		}
		if ($category_id > 0) {
			$c.="<tr>\n";
				$c.="<td width='16'><img src='".$wb."modules/documents/images/default/menu_delete_folder.png'></td>\n";
				$c.="<td><a href='index.php?module=documents&task=confirm_folder_delete&category_id=".$category_id."'>"._DOCUMENTS_MENU_DELETE_FOLDER_."</a></td>\n";
			$c.="</tr>\n";
		}
		if ($category_id == 0 && CheckAccess($wui->RoleID(),$module_id,"acl")) {
			$c.="<tr>\n";
				$c.="<td width='16'><img src='".$wb."modules/documents/images/default/menu_acl.png'></td>\n";
				$c.="<td><a href='index.php?module=documents&task=acl'>"._DOCUMENTS_MENU_MODULE_ACL_."</a></td>\n";
			$c.="</tr>\n";
		}
	$c.="</table>\n";

	return $c;
}