<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."include/functions/misc/filetype_image.php");
require_once($GLOBALS['dr']."include/functions/filesystem/size_int.php");
//require_once($GLOBALS['dr']."classes/design/right_click.php");
require_once($GLOBALS['dr']."classes/form/show_results.php");

require_once($GLOBALS['dr']."modules/documents/functions/category_max_document_ids.php");
require_once($GLOBALS['dr']."modules/documents/functions/browse/display_document_rating.php");

function DisplayFiles($par_category_id) {
	$s="";
	$db=$GLOBALS['db'];
	$ds=$GLOBALS['ds'];
	$dus=$GLOBALS['dus'];
	$wb=$GLOBALS['wb'];
	$user_id=$_SESSION['user_id'];

	//$document_id_in=CategoryMaxDocumentIDS($par_category_id);
	//echo $document_id_in;
	if (EMPTY($document_id_in)) { $document_id_in="''"; }
	/* FILENAME SORTING */
	if (!ISSET($_POST['sort_filename'])) { $v_sql_sort_filename="ASC"; } else { $v_sql_sort_filename=$_POST['sort_filename']; }
	/* SIZE SORTING */
	if (!ISSET($_POST['sort_size'])) { $v_sql_sort_size=",filesize ASC"; } else { $v_sql_sort_size=",filesize ".$_POST['sort_size']; }
	/* SIZE SORTING */
	if (!ISSET($_POST['sort_version'])) { $v_sql_sort_version=",version_number ASC"; } else { $v_sql_sort_version=",version_number ".$_POST['sort_version']; }
	/* OWNER SORTING */
	if (!ISSET($_POST['sort_owner'])) { $v_sql_sort_owner=",full_name ASC"; } else { $v_sql_sort_owner=",full_name ".$_POST['sort_owner']; }
	/* STATUSSORTING */
	if (!ISSET($_POST['sort_status'])) { $v_sql_sort_status=",status_name ASC"; } else { $v_sql_sort_status=",status_name ".$_POST['sort_status']; }
	/* FILTER / SEARCH FOR DESCRIPTION */
	if (ISSET($_POST['filter']) && $_POST['filter']!="Quick Search...") { $v_sql_filter="AND df.filename LIKE '%".EscapeData($_POST['filter'])."%'"; } else { $v_sql_filter=""; }
	/* FILTER TYPE */
	if (ISSET($_GET['filter_type']) && !EMPTY($_GET['filter_type'])) { $v_sql_filter_type="AND df.filename LIKE '%".EscapeData($_GET['filter_type'])."'"; } else { $v_sql_filter_type=""; }
	if (ISSET($_POST['filter_type']) && !EMPTY($_POST['filter_type'])) { $v_sql_filter_type="AND df.filename LIKE '%".EscapeData($_POST['filter_type'])."'"; } else { $v_sql_filter_type=""; }

	/* DATABASE QUERY */
	$sql="SELECT df.document_id, 'checkbox' as checkbox, df.title, df.user_id, df.filename, df.filesize, df.checked_out, df.version_number,
				df.user_id_checked_out, df.date_checked_out, df.locked, df.user_id_locked, df.date_locked,
				dsm.status_name, dsm.is_pending, dsm.is_current,
				um.full_name as owner
				FROM ".$GLOBALS['database_prefix']."document_files df, ".$GLOBALS['database_prefix']."document_status_master dsm,
				".$GLOBALS['database_prefix']."user_master um
				WHERE df.latest_version = 'y'
				AND df.category_id = ".$par_category_id."
				AND df.status_id = dsm.status_id
				AND (dsm.is_pending = 'y' OR dsm.is_current = 'y')
				AND df.user_id = um.user_id
				".$v_sql_filter."
				".$v_sql_filter_type."
				";
	//echo $sql."<br>";
	//ORDER BY df.filename ".$v_sql_sort_filename." ".$v_sql_sort_size." ".$v_sql_sort_owner." ".$v_sql_sort_status."
	//".$v_sql_sort_version."

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","","File Name","Size","Version","Owner","Status")); /* COLS */
	$sr->Columns(array("document_id","checkbox","filename","filesize","version_number","owner","status_name"));
	$sr->Query($sql);

	for ($i=0;$i<$sr->CountRows();$i++) {
		$document_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */

		/**/
		
		
		/* THIS IS THE LINK TO THE ACTUAL CATEGORY */
		$sr->ModifyData($i,1,"<input type='checkbox' name='document_id[]' value='".$document_id."'>");

		/* THIS DISPLAYS THE HEIRARCHY OF THE CATEGORIES */
		//$ci=new CategoryID;
		//$ci->SetParameters($category_id);
		//$var=$ci->CategoryHeirarchy($category_id);
		//$sr->ModifyData($i,2,$var);

		/* THIS ADDS IN THE DELETE LINE */
		//$sr->ModifyData($i,3,"<a href='index.php?module=documents&task=my_bookmarks&subtask=delete&category_id=".$category_id."' title='Delete'>Delete</a>");
	}
	$sr->RemoveColumn(0);
	
	/* PUT IN THE BUTTONS */
	if (!defined( '_VALID_MVH_MOBILE_' )) {
	 	$s.="<form name='display_files' method='post' action='index.php?module=documents&task=home&category_id=".$par_category_id."'>\n";
		$s.="<table>\n";
			$s.="<tr>\n";
				$s.="<td class='colhead' colspan='11'>\n";
				$s.="<input name='button' value='lock_document' type='image' src='modules/documents/images/".$GLOBALS['ds']->GetInfo("theme")."/button_lock_doc.gif' alt='Lock document' border='0'>\n";
				$s.="<input name='button' value='unlock_document' type='image' src='modules/documents/images/".$GLOBALS['ds']->GetInfo("theme")."/button_unlock_doc.gif' alt='UnLock document' border='0'>\n";
				$s.="<input name='button' value='cut_document' type='image' src='modules/documents/images/".$GLOBALS['ds']->GetInfo("theme")."/button_cut.gif' alt='Move documents - this will lock the file' border='0'>\n";
				$s.="<input name='button' value='check_out' type='image' src='modules/documents/images/".$GLOBALS['ds']->GetInfo("theme")."/button_check_out.gif' alt='Check out documents' border='0'>\n";
				$s.="<input name='button' value='delete_version' type='image' src='modules/documents/images/".$GLOBALS['ds']->GetInfo("theme")."/button_delete_version.gif' alt='Delete only latest version' border='0'>\n";
				$s.="<input name='button' value='delete_file' type='image' src='modules/documents/images/".$GLOBALS['ds']->GetInfo("theme")."/button_delete.gif' alt='Delete entire file and versions' border='0'>\n";
				$s.="</td>\n";
			$s.="</tr>\n";
		$s.="</table>\n";
	}
	
	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Files in this category");
	$sr->Footer();
	$s.=$sr->Draw();
	
	$s.="</form>";
		
	return $s;
}


?>