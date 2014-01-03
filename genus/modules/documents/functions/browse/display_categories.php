<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."include/functions/db/count_rows.php");
require_once($GLOBALS['dr']."include/functions/db/row_exists.php");
require_once($GLOBALS['dr']."classes/design/right_click.php");
require_once($GLOBALS['dr']."modules/documents/classes/category_id.php");

function DisplayCategories($par_category_id,$par_type,$par_id,$break_after=5) {

	$db=$GLOBALS['db'];
	$wb=$GLOBALS['wb'];

	$s="<script language='javascript' src='".$GLOBALS["wb"]."include/functions/javascript/right_click/contextmenu.js'></script>\n";

	/* WE ONLY SHOW EITHER THE TEAMSPACE ID OR THE WORKSPACE, NO NEED FOR BOTH */
	if ($par_type == "teamspace") {
		$v_sql="AND dc.teamspace_id = '".$par_id."'";
	}
	else {
		$tb=$GLOBALS['database_prefix']."workspace_users u";
		$v_sql="AND dc.workspace_id = '".$par_id."'";
	}

	$sql="SELECT dc.category_id, dc.category_name, dc.locked
				FROM ".$GLOBALS['database_prefix']."document_categories dc
				WHERE parent_id = '".$par_category_id."'
				$v_sql
				AND teamspace_id ".$GLOBALS['teamspace_sql']."
				ORDER BY dc.category_name
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	$s.="<table class='teamspace' cellpadding='0' cellspacing='5' width='500'>\n";
		/* THIS IS THE CATEGORY HEADING */
		$v_back_category_id=GetColumnValue("parent_id","document_categories","category_id",$par_category_id,"");
		$s.="<tr>\n";
			$s.="<td class='colhead' align='center' colspan='10'>Document Folders";
			$s.="</td>\n";
		$s.="</tr>\n";
		/* DISPLAY THE HEIRARCHY */
		$ci=new CategoryID($par_category_id);
		$s.="<tr>\n";
			$s.="<td colspan='10'><a href='index.php?module=documents&task=home'>Home</a> -> ".$ci->CategoryHeirarchy($par_category_id)."</td>\n";
		$s.="</tr>\n";
		if ($db->NumRows($result) > 0) {
			$count=0;
			$count_t=0;
			while($row = $db->FetchArray($result)) {

				/* DRAW THE RIGHT CLICK MENU FOR NON MOBILE ONLY */
				if (!defined( '_VALID_MVH_MOBILE_' )) {

					$dm=new DrawMenu;
					$dm->DrawTopMenu($row['category_id']);
					$dm->DrawContent("contextmenuhead","","","","Category: ".$row['category_name']);
					$dm->DrawContent("","","index.php?module=documents&task=home&action=delete_category&del_category_id=".$row['category_id'],"","Delete");
					$dm->DrawBottomMenu();
					$s.=$dm->DrawMenuFinal();

				}

				$cid=new CategoryID();
				$cid->SetParameters($row['category_id']);
				if ($cid->CategoryRoleExists()) {
					$count_t++;
					/*
						COUNT THE NUMBER OF DOCUMENTS IN THE CATEGORY TO CHANGE THE ICON
					*/
					if (CountRows("document_files","category_id",$row['category_id'],"") > 0) {
						if (RowExists("document_user_bookmarks","category_id",$row['category_id'],"AND user_id = '".$GLOBALS['user_id']."'")) {
							$icon="icon_folder_full_bookmark";
						}
						else {
							$icon="icon_folder_full";
						}
					}
					else {
						/* CHECK IF THE USER HAS BOOKMARKED THIS FOLDER */
						if (RowExists("document_user_bookmarks","category_id",$row['category_id'],"AND user_id = '".$GLOBALS['user_id']."'")) {
							$icon="icon_folder_bookmark";
						}
						else {
							$icon="icon_folder_empty";
						}
					}
					if ($row['locked']=="y") {
						$icon="icon_folder_locked";
					}


					if ($count==0) {
						$s.="<tr>\n";
					}

					/* THIS IS THE BREAKER LINE */
					if ($count > 0) {
						$s.="<td width='1' bgcolor='#BEC0CF'><img src='images/spacer.gif' width='1' height='1'></td>\n";
					}
					/* THIS IS THE FOLDER LINE */
					if (defined( '_VALID_MVH_MOBILE_' )) {
						$s.="<td align='center'><a href='index.php?module=documents&task=home&category_id=".$row['category_id']."'><img src='modules/documents/images/".$GLOBALS['ds']->GetInfo("theme")."/".$icon.".png' width='16' height='16' border='0'><br>".$row['category_name']."</a></td>\n";
					}
					else {
						//$s.="<td height='90' width='75' align='center' onMouseOver=\"document.getElementById('document_".$row['category_id']."').className='showBlk'\" onMouseOut=\"document.getElementById('document_".$row['category_id']."').className='hideBlk'\"><a href='index.php?module=documents&task=home&category_id=".$row['category_id']."'><img src='modules/documents/images/".$GLOBALS['ds']->GetInfo("theme")."/".$icon.".png' width='32' height='32' border='0'><br>".$row['category_name']."</a><br><div height='20' id='document_".$row['category_id']."' class='hideBlk'><img src='images/home/teamspace_arrow_up.gif'></div></td>\n";
						$s.="<td height='70' width='75' align='center' onmouseover=\"initpage(".$row['category_id'].")\"><a href='index.php?module=documents&task=home&category_id=".$row['category_id']."'><img src='modules/documents/images/".$GLOBALS['ds']->GetInfo("theme")."/".$icon.".png' width='32' height='32' border='0'><br>".$row['category_name']."</a></td>\n";
					}
					$count++;
					if ($count==$break_after) {
						$s.="</tr>\n";
						$count=0;
					}
				}
			}
		}
		else {
			$s.="<tr>\n";
				$s.="<td colspan='10'>No folders.</td>\n";
			$s.="</tr>\n";
		}
	$s.="</table>\n";
	return $s;
}

?>