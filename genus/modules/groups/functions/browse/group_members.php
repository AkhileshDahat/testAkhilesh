<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($dr."classes/design/right_click.php");

function GroupMembers($user_id,$workspace_id,$teamspace_id,$group_id) {
	$db=$GLOBALS['db'];

	$sql="SELECT gm.group_id, um.full_name
				FROM ".$GLOBALS['database_prefix']."group_master gm, ".$GLOBALS['database_prefix']."group_member_master gmm,
				".$GLOBALS['database_prefix']."core_user_master um
				WHERE gm.group_id = '".$group_id."'
				AND gm.user_id = ".$user_id."
				AND gm.workspace_id = ".$workspace_id."
				AND gm.teamspace_id ".$GLOBALS['teamspace_sql']."
				AND gm.group_id = gmm.group_id
				AND gmm.user_id = um.user_id
				ORDER BY um.surname
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	$s="<table class='plain' width='500'>\n";
		$s.="<tr>\n";
			$s.="<td class='bold' colspan='10'>You have added the following members to the group:</td>\n";
		$s.="</tr>\n";
		$s.="<tr class='colhead'>\n";
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
					$s.="<td>".$count++.".</td>\n";
					$s.="<td>".$row['full_name']."</td>\n";
				$s.="</tr>\n";

			}
		}
	$s.="</table>\n";
	return $s;
}

?>