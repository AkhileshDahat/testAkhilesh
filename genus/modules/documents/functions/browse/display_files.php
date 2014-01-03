<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."include/functions/misc/filetype_image.php");
require_once($GLOBALS['dr']."include/functions/filesystem/size_int.php");
//require_once($GLOBALS['dr']."classes/design/right_click.php");

require_once($GLOBALS['dr']."modules/documents/classes/category_role_security.php");

require_once($GLOBALS['dr']."modules/documents/functions/category_max_document_ids.php");
require_once($GLOBALS['dr']."modules/documents/functions/browse/display_document_rating.php");

function DisplayFiles($par_category_id) {
	$db=$GLOBALS['db'];
	$ds=$GLOBALS['ds'];
	$dus=$GLOBALS['dus'];
	$wb=$GLOBALS['wb'];
	$user_id=$_SESSION['user_id'];

	/* ROLE PRIVILEGE OBJECT */
	$obj_crs=new CategoryRoleSecurity;
	$result_crs=$obj_crs->SetParameters($par_category_id,$GLOBALS['wui']->GetColVal("role_id"));
	if (!$result_crs) { return $obj_crs->ShowErrors()." Access Denied"; }

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
	$sql="SELECT df.document_id, df.title, df.user_id, df.filename, df.filesize, df.checked_out, df.version_number,
				df.user_id_checked_out, df.date_checked_out, df.locked, df.user_id_locked, df.date_locked,
				dsm.status_name, dsm.is_pending, dsm.is_current,
				um.full_name
				FROM ".$GLOBALS['database_prefix']."document_files df, ".$GLOBALS['database_prefix']."document_status_master dsm,
				".$GLOBALS['database_prefix']."core_user_master um
				WHERE df.latest_version = 'y'
				AND df.category_id = ".$par_category_id."
				AND df.status_id = dsm.status_id
				AND (dsm.is_pending = 'y' OR dsm.is_current = 'y')
				AND df.user_id = um.user_id
				".$v_sql_filter."
				".$v_sql_filter_type."
				ORDER BY df.filename ".$v_sql_sort_filename." ".$v_sql_sort_size." ".$v_sql_sort_owner." ".$v_sql_sort_status."
				".$v_sql_sort_version."
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	$s="<table class='plain' width='500' cellpadding='2' cellspacing='0'>\n";
		$s.="<form name='display_files' method='post' action='index.php?module=documents&task=home&category_id=".$par_category_id."'>\n";
		$s.="<tr>\n";
			$s.="<td class='colhead' align='center' colspan='11'>Documents in this category\n";
			if (ISSET($_POST['filter'])) { $v_filter=EscapeData($_POST['filter']); } else { $v_filter="Quick Search...";	}
			//$s.="<td class='colhead' align='center' colspan='2'>\n";
			$s.="<input type='text' name='filter' id='filter' value='".$v_filter."' class='input' onClick=document.getElementById('filter').value=\"\">\n";
			$s.="<select name='filter_type' onChange='this.form.submit()'><option value=''>Type...</option><option value='pdf'>PDF</option><option value='doc'>MS Word</option><option value='zip'>ZIP</option></select>\n";
			$s.="</td>\n";
		$s.="</tr>\n";
		if (!defined( '_VALID_MVH_MOBILE_' )) {
			$s.="<tr>\n";
				$s.="<td class='colhead' colspan='11'>\n";
				/* CAN THE USER UPLOAD FILES IN THIS CATEGORY */
				if ($obj_crs->GetColVal("upload") == "y") {
					$s.="<input name='button' value='lock_document' type='image' src='modules/documents/images/".$GLOBALS['ds']->GetInfo("theme")."/button_lock_doc.gif' alt='Lock document' border='0'>\n";
					$s.="<input name='button' value='unlock_document' type='image' src='modules/documents/images/".$GLOBALS['ds']->GetInfo("theme")."/button_unlock_doc.gif' alt='UnLock document' border='0'>\n";
					$s.="<input name='button' value='cut_document' type='image' src='modules/documents/images/".$GLOBALS['ds']->GetInfo("theme")."/button_cut.gif' alt='Move documents - this will lock the file' border='0'>\n";
					$s.="<input name='button' value='check_out' type='image' src='modules/documents/images/".$GLOBALS['ds']->GetInfo("theme")."/button_check_out.gif' alt='Check out documents' border='0'>\n";
				}
				if ($obj_crs->GetColVal("delete_files") == "y") {
					$s.="<input name='button' value='delete_version' type='image' src='modules/documents/images/".$GLOBALS['ds']->GetInfo("theme")."/button_delete_version.gif' alt='Delete only latest version' border='0'>\n";
					$s.="<input name='button' value='delete_file' type='image' src='modules/documents/images/".$GLOBALS['ds']->GetInfo("theme")."/button_delete.gif' alt='Delete entire file and versions' border='0'>\n";
				}
				$s.="</td>\n";
			$s.="</tr>\n";
		}

		if ($db->NumRows($result) > 0) {
			$s.="<tr bgcolor='#e5e5e5'>\n";
				$s.="<td></td>\n";
				$s.="<td></td>\n";
				$s.="<td></td>\n";
				$s.="<td></td>\n";
				$s.="<td></td>\n";

				$s.="<td><input type='submit' name='ButtonFilenameSort' value='Filename' class='buttoninvisible'></td>\n";
				/* COLUMN SORTING */
				if (!ISSET($_POST['sort_filename'])) { $sort_filename="ASC"; } else { if ($_POST['sort_filename']=="ASC") { $sort_filename="DESC"; } else { $sort_filename="ASC"; } }
				if (!ISSET($_POST['ButtonFilenameSort']) && ISSET($_POST['sort_filename'])) { $sort_filename=$_POST['sort_filename']; }
				$s.="<input type='hidden' name='sort_filename' value='".$sort_filename."'>\n";

				//if ($dus->GetInfo("col_size") == "y") {
				$s.="<td><input type='submit' name='ButtonSizeSort' value='Size' class='buttoninvisible'></td>\n";
				/* COLUMN SORTING */
				if (!ISSET($_POST['sort_size'])) { $sort_size="ASC"; } else { if ($_POST['sort_size']=="ASC") { $sort_size="DESC"; } else { $sort_size="ASC"; } }
				if (!ISSET($_POST['ButtonSizeSort']) && ISSET($_POST['sort_size'])) { $sort_size=$_POST['sort_size']; }
				$s.="<input type='hidden' name='sort_size' value='".$sort_size."'>\n";
				//}

				$s.="<td><input type='submit' name='ButtonVerSort' value='Version' class='buttoninvisible'></td>\n";
				/* COLUMN SORTING */
				if (!ISSET($_POST['sort_version'])) { $sort_version="ASC"; } else { if ($_POST['sort_version']=="ASC") { $sort_version="DESC"; } else { $sort_version="ASC"; } }
				if (!ISSET($_POST['ButtonVersionSort']) && ISSET($_POST['sort_version'])) { $sort_version=$_POST['sort_version']; }
				$s.="<input type='hidden' name='sort_version' value='".$sort_version."'>\n";

				//if ($dus->GetInfo("col_owner") == "y") {
					$s.="<td><input type='submit' name='ButtonOwnerSort' value='Owner' class='buttoninvisible'></td>\n";
					/* COLUMN SORTING */
					if (!ISSET($_POST['sort_owner'])) { $sort_owner="ASC"; } else { if ($_POST['sort_owner']=="ASC") { $sort_owner="DESC"; } else { $sort_owner="ASC"; } }
					if (!ISSET($_POST['ButtonOwnerSort']) && ISSET($_POST['sort_owner'])) { $sort_owner=$_POST['sort_owner']; }
					$s.="<input type='hidden' name='sort_owner' value='".$sort_owner."'>\n";
				//}

				$s.="<td><input type='submit' name='ButtonStatusSort' value='Status' class='buttoninvisible'></td>\n";
				/* COLUMN SORTING */
				if (!ISSET($_POST['sort_status'])) { $sort_status="ASC"; } else { if ($_POST['sort_status']=="ASC") { $sort_status="DESC"; } else { $sort_status="ASC"; } }
				if (!ISSET($_POST['ButtonStatusSort']) && ISSET($_POST['sort_status'])) { $sort_status=$_POST['sort_status']; }
				$s.="<input type='hidden' name='sort_status' value='".$sort_status."'>\n";

				if ($ds->GetInfo("show_document_rating") == "y" && $dus->GetInfo("col_rating") == "y") {
					$s.="<td>Rating</td>\n";
				}
			$s.="</tr>\n";
			while($row = $db->FetchArray($result)) {
				/*
					I NEED TO CONSIDER REWRITING THIS WHOLE SECTION TO RATHER GRAB JUST THE DOCUMENT ID'S AND
					USE THE DOCUMENT CLASS TO RETRIEVE THE DATA
				*/
				/*
					CREATE A NEW OBJECT AT THIS POINT STRICTLY FOR CUTTING
				*/
				$di=new DocumentID;

				/* CHECK IF THE DOCUMENT IS PUBLISHED OR THE DOCUMENT IS PENDING AND THIS IS THE OWNER BROWSING */
				//echo $row['user_id'];
				if ($row['is_current'] == "y" || ($row['is_pending'] == "y" && $row['user_id'] == $user_id)) {

					/* DRAW THE RIGHT CLICK MENU FOR NON MOBILE ONLY */
					if (!defined( '_VALID_MVH_MOBILE_' )) {

						$dm=new DrawMenu;
						$dm->DrawTopMenu($row['document_id']);
						if (STRLEN($row['filename']) > 15) { $heading=SUBSTR($row['filename'],0,15)."..."; } else { $heading=$row['filename']; }
						$dm->DrawContent("contextmenuhead","","","",$heading);
						$dm->DrawContent("","","index.php?module=documents&task=document_details&document_id=".$row['document_id'],"","Document Details");
						/* PUBLISH DOCUMENT IF THIS IS THE OWNER */
						if ($row['is_pending'] == "y" && $row['user_id'] == $user_id) {
							$dm->DrawContent("","","modules/documents/bin/publish_document.php?document_id=".$row['document_id']."&category_id=".$par_category_id,"","Publish Document");
						}
						if ($row['is_current'] == "y" && $row['user_id'] == $user_id) {
							//$dm->DrawContent("","","modules/documents/bin/unpublish_document.php?document_id=".$row['document_id']."&category_id=".$par_category_id,"","Unpublish Document");
						}
						/* BREAK */
						$dm->DrawContent("contextmenusep","","","","","");
						if ($row['checked_out'] != "y") {
							$dm->DrawContent("","","modules/documents/bin/checkout_document.php?document_id=".$row['document_id']."&category_id=".$par_category_id,"","Check-out Document");
						}
						/* CHECK FOR SECURITY ON THE CATEGORY */
						if ($obj_crs->GetColVal("delete_files") == "y") {
							$dm->DrawContent("","","modules/documents/bin/delete_document_version.php?document_id=".$row['document_id']."&category_id=".$par_category_id,"","Delete Version");
						}

						$dm->DrawBottomMenu();
						$s.=$dm->DrawMenuFinal();
					}

					/* CHECK IF THE DOCUMENT HAS BEEN CHECKED OUT	*/
					if ($row['checked_out'] == "y") {
						$checked_out="<img src='".$wb."images/nuvola/16x16/actions/revert.png'>";
						$checked_out_user=GetColumnValue("full_name","core_user_master","user_id",$row['user_id_checked_out'],"");
						$checked_out_alert="onclick=\"alert('Checked out by: ".$checked_out_user."\\nOn: ".$row['date_checked_out']."');\"";
					}
					else {
						$checked_out="";
						$checked_out_user="";
						$checked_out_alert="";
					}
					/* CHECK IF THE DOCUMENT HAS BEEN LOCKED	*/
					if ($row['locked'] == "y") {
						$locked_img="<img src='".$wb."images/nuvola/16x16/actions/encrypted.png'>";
						$locked_user=GetColumnValue("full_name","core_user_master","user_id",$row['user_id_locked'],"");
						$locked_alert="onclick=\"alert('Locked by: ".$locked_user."\\nOn: ".$row['date_locked']."');\"";
					}
					else {
						$locked_img="";
						$locked_user="";
						$locked_alert="";
					}
					/* SHOW A FRIENDLY FILE NAME WITHOUT THE EXTENSION */
					if (EMPTY($row['title'])) { $friendly_title=SUBSTR($row['filename'],0,-4); } else { $friendly_title=$row['title']; }
					/* LIMIT THE FILE NAME LENGTH */
					if (STRLEN($friendly_title) > 20) { $friendly_title=substr($friendly_title,0,20)."..."; }
					if (!defined( '_VALID_MVH_MOBILE_' )) {
						$s.="<tr onmouseover=\"initpage(".$row['document_id'].");this.className='alternateover'\" onMouseOut=\"this.className=''\">\n";
					}
					else {
						$s.="<tr>\n";
					}
						$s.="<td><input type='checkbox' name='document_id[]' value='".$row['document_id']."'></td>\n";
						$s.="<td $checked_out_alert>".$checked_out."</td>\n";
						$s.="<td $locked_alert>".$locked_img."</td>\n";
						/* DOCUMENT CUT CHECK*/
						if ($di->DocumentIsCut($row['document_id'])) { $v_cut_icon="<img src='".$wb."images/nuvola/16x16/actions/cut.png' title='You have cut this document' onClick=\"alert('You have cut this document')\">"; } else { $v_cut_icon=""; }
						$s.="<td>".$v_cut_icon."</td>\n";
						/* FILE TYPE IMAGE */
						$s.="<td><a href='index.php?module=documents&task=home&category_id=".$par_category_id."&filter_type=".SUBSTR($row['filename'], -3)."'>".FiletypeImage(SUBSTR($row['filename'], -3),"Click to filter by type")."</a></td>\n";
						if ($row['checked_out'] == "y" && $row['user_id_checked_out'] != $user_id) {
							$s.="<td>".$friendly_title."</td>\n";
						}
						else {
							/* IF LOCKED, THE FILE CANNOT BE DOWNLOADED */
							if ($row['locked'] == "y") {
								$s.="<td>".$friendly_title."</td>\n";
							}
							else {
								$s.="<td><a href='modules/documents/bin/download_document.php?document_id=".$row['document_id']."'>".$friendly_title."</a></td>\n";
							}
						}
						//if ($dus->GetInfo("col_size") == "y") {
							$s.="<td align='center'>".SizeFromInt($row['filesize'])."</td>\n";
						//}
						$s.="<td align='center'>".$row['version_number']."</td>\n";
						//if ($dus->GetInfo("col_owner") == "y") {
							$s.="<td>".$row['full_name']."</td>\n";
						//}
						$s.="<td>".$row['status_name']."</td>\n";
						if ($ds->GetInfo("show_document_rating") == "y" && $dus->GetInfo("col_rating") == "y") {
							//$s.="<td><img src='modules/documents/bin/rating/display_document_rating.php?document_id=".$row['document_id']."'></td>\n";
							//$s.=DisplayDocumentRating($row['document_id']);
							$s.="<td><iframe width='100' height='25' src='modules/documents/bin/rating/display_document_rating.php?document_id=".$row['document_id']."' scrolling='no' frameborder='no'></iframe></td>\n";
						}
					$s.="</tr>\n";
				}

			}
		}

		$s.="</form>\n";
	$s.="</table>\n";
	return $s;
}


?>