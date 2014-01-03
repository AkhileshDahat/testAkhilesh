<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* INCLUDE THE MODULE CONFIG FILE */
require_once($GLOBALS['dr']."modules/documents/config.php");

require_once($GLOBALS['dr']."modules/documents/classes/document_setup.php");

/* INCLUDE THE USER CONFIGURATION SETTINGS FOR THE MODULE */
require_once($GLOBALS['dr']."modules/documents/classes/documents_user_settings.php");

/* OTHER USED CLASSES */
require_once($GLOBALS['dr']."modules/documents/classes/category_id.php");

/* ACL */
require_once $GLOBALS['dr']."include/functions/acl/check_access.php";

/* THIS IS THE MENU FUNCTION WHICH IS UNIQUE TO THIS MODULE */
require_once $GLOBALS['dr']."modules/documents/functions/menu.php";

function LoadModule($module_id) {

	GLOBAL $dus,$ds;
	GLOBAL $raduploadplus;
	$raduploadplus=1;
	echo "Rad:".$GLOBALS['raduploadplus'];

	$c="";

	/* CHECK SECURITY IF WE HAVE A CATEGORY OR DOCUMENT IN THE QUERYSTRING OR POSTED */
	if (ISSET($_GET['category_id']) && $_GET['category_id'] > 0) {
		$cid=new CategoryID();
		$cid->SetParameters($_GET['category_id']);
		if (!$cid->CategoryRoleExists()) {
			$c.="<div style=\"width:100%;height:250px;background-color:grey;color:white;font-size:18px\">Sorry, access to this category is denied.</div>";
			return $c;
		}
	}

	/* DOCUMENT SETUP */
	$ds=new DocumentSetup();

	/* USER SETUP MUST COME AFTER THE ABOVE BECAUSE THERE WE CHANGE VALUES */
	$dus=new DocumentsUserSettings;
	$dus->SetParameters($_SESSION['user_id']);

	/*
		THIS IS WHERE WE PROCESS ANY RIGHT HAND COLUMN ACTIONS.
	*/

	if (ISSET($_GET['action'])) {
		/* BOOKMARK THE CATEGORY ID */
		if ($_GET['action']=="bookmark") {
			require_once $GLOBALS['dr']."modules/documents/classes/bookmark_id.php";
			$bi=new BookmarkID;
			$bi->SetParameters($_GET['category_id']);
			$result=$bi->Add();
			if ($result) {
				$c.="Success";
			}
			else {
				$c.="Failed to bookmark";
				$c.=$bi->ShowErrors();
			}
		}
		else if ($_GET['action']=="show_rad_upload") {
			$result=$GLOBALS['dus']->ChangeShowRadUpload($_GET['enable']);
			if ($result) {
				$c.="Success";
			}
			else {
				$c.="Failed to bookmark";
				$c.=$GLOBALS['dus']->ShowErrors();
			}
		}
		else if ($_GET['action']=="paste_documents") {
			require_once $GLOBALS['dr']."modules/documents/classes/paste_documents.php";
			//echo "pasting now<br>";
			$o_pd=new PasteDocuments;
			$o_pd->SetParameters($_GET['category_id']);
			$result_paste=$o_pd->Paste();
		}
	}

	/* CALL THE SETTINGS ONCE ANY POSSIBLE CHANGES HAVE BEEN MADE */
	$dus->Info();

	/* END PROCESSING */

	/* STORE THE GLOBAL MODULE ID WHICH WE ARE IN NOW */
	GLOBAL $module_id;
	$module_id=GetColumnValue("module_id","core_module_master","name",$_GET['module']);

	$c.="<table>\n";
		$c.="<tr>\n";
			if (ISSET($_GET['task']) && CheckAccess($GLOBALS['wui']->RoleID(),$module_id,$_GET['task'])) {
				$c.="<td width='90%' valign='top' id=slogan>\n";
				$file_inc=$GLOBALS['dr']."modules/documents/modules/".$_GET['task'].".php";
				if (file_exists($file_inc)) {
					require_once($file_inc);
					if (function_exists("LoadTask")) {
						/* IF BROWSING A CATEGORY CHECK THE ACL */
						if (!EMPTY($_GET['category_id'])) {
							$c.=LoadTask($module_id);
						}
						/* HERE THEY'RE DOING SOMETHING OTHER THAN BROWSING THE CATEGORIES */
						else {
							$c.=LoadTask($module_id);
						}
					}
				}
				$c.="</td>\n";
			}
				else {
					$c.="<td>Access Denied</td>";
			}
			/*
				THIS IS THE MENU ON THE RIGHT AND THE UPLOAD BOX
			*/
			$c.="<td width='150' valign='top'>\n";
			if (ISSET($_GET['category_id']) && IS_NUMERIC($_GET['category_id'])) { $category_id=$_GET['category_id']; } else { $category_id=0; }
			if (defined( '_VALID_MVH_MOBILE_' )) {
				$c.=Menu($_SESSION['user_id'],$category_id);
			}
			else {
				$c.=Menu($_SESSION['user_id'],$category_id);
			}
			if ($dus->GetInfo("show_rad_upload") == "y" && ISSET($_GET['category_id']) && IS_NUMERIC($_GET['category_id']) && $_GET['category_id'] > 0 && !defined( '_VALID_MVH_MOBILE_' )) {
				$c.="<br>";
				require_once $GLOBALS['dr']."modules/documents/functions/rad_upload/upload_applet.php";
				$c.=UploadApplet($GLOBALS['wb']."modules/documents/bin/rad_upload/documents_teamspace.php?category_id=".$_GET['category_id']."&PHPSESSID=".session_id());
			}
			$c.="</td>\n";
		$c.="</tr>\n";
	$c.="</table>\n";

	return $c;
}
?>