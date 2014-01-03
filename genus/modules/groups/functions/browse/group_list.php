<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."include/functions/misc/yes_no_image.php");
require_once($GLOBALS['dr']."classes/design/right_click.php");

function GroupList($user_id,$workspace_id,$teamspace_id) {
	$db=$GLOBALS['db'];

	$sql="SELECT gm.group_id, gm.group_name
				FROM ".$GLOBALS['database_prefix']."group_master gm
				WHERE gm.user_id = ".$user_id."
				AND gm.workspace_id = ".$workspace_id."
				AND gm.teamspace_id ".$GLOBALS['teamspace_sql']."
				ORDER BY gm.group_name
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	$s="<table class='plain'>\n";
		$s.="<tr>\n";
			$s.="<td class='bold' colspan='3'>Your groups</td>\n";
		$s.="</tr>\n";
		$s.="<tr class='colhead'>\n";
			$s.="<td width='5%'></td>\n";
			$s.="<td width='5%'></td>\n";
			$s.="<td>Name</td>\n";
		$s.="</tr>\n";
		if ($db->NumRows($result) > 0) {
			$count=1;
			while($row = $db->FetchArray($result)) {
				/*
					DRAW THE RIGHT CLICK MENU
				*/
				$dm=new DrawMenu;
				$dm->DrawTopMenu($row['group_id']);
				/* BREAK */
				$dm->DrawContent("","","index.php?module=groups&task=add_members&group_id=".$row['group_id'],"","Add Members");
				$dm->DrawContent("contextmenusep","","","","","");
				$dm->DrawContent("","","index.php?module=groups&task=group_list&subtask=delete&group_id=".$row['group_id'],"","Delete Group");
				$dm->DrawBottomMenu();
				$s.=$dm->DrawMenuFinal();

				$s.="<tr onmouseover=\"initpage(".$row['group_id'].");this.className='alternateover'\" onMouseOut=\"this.className=''\"\">\n";
					$s.="<td><input type='checkbox' name='group_id[]' value='".$row['group_id']."'></td>\n";
					$s.="<td>".$count++.".</td>\n";
					$s.="<td>".$row['group_name']."</td>\n";
				$s.="</tr>\n";

			}
		}
	$s.="</table>\n";
	return $s;
}

?>