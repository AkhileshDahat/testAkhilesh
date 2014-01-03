<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/design/right_click.php");

function Messages($type,$limit,$offset) {

	$db=$GLOBALS['db'];
	if ($type=="sent") {
		$sql="SELECT mm.message_id,mm.message,um.full_name, date_sent
					FROM ".$GLOBALS['database_prefix']."message_master mm, ".$GLOBALS['database_prefix']."core_user_master um
					WHERE mm.user_id_from = ".$_SESSION['user_id']."
					AND mm.workspace_id = ".$GLOBALS['workspace_id']."
					AND mm.teamspace_id ".$GLOBALS['teamspace_sql']."
					AND mm.user_id_to = um.user_id
					ORDER BY mm.message_id DESC
					LIMIT ".$limit." OFFSET ".$offset."
					";
	}
	else {
		$sql="SELECT mm.message_id,mm.message,um.full_name, date_sent
					FROM ".$GLOBALS['database_prefix']."message_master mm, ".$GLOBALS['database_prefix']."core_user_master um
					WHERE mm.user_id_to = ".$_SESSION['user_id']."
					AND mm.workspace_id = ".$GLOBALS['workspace_id']."
					AND mm.teamspace_id ".$GLOBALS['teamspace_sql']."
					AND mm.user_id_from = um.user_id
					ORDER BY mm.message_id DESC
					LIMIT ".$limit." OFFSET ".$offset."
					";
	}
	//echo $sql."<br>";
	$result = $db->Query($sql);
	$s="<table class='plain'>\n";
		$s.="<tr class='colhead'>\n";
			$s.="<td width='5%'></td>\n";
			$s.="<td>User</td>\n";
			$s.="<td>Message</td>\n";
			$s.="<td>Date</td>\n";
		$s.="</tr>\n";
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				/*
					DRAW THE RIGHT CLICK MENU
				*/
				/*
				$dm=new DrawMenu;
				$dm->DrawTopMenu($row['message_id']);
				$dm->DrawContent("","","index.php?module=groups&task=add_members&message_id=".$row['message_id'],"","Add Members");
				$dm->DrawContent("contextmenusep","","","","","");
				$dm->DrawContent("","","index.php?module=groups&task=group_list&subtask=delete&message_id=".$row['message_id'],"","Delete Group");
				$dm->DrawBottomMenu();
				$s.=$dm->DrawMenuFinal();
				*/

				$s.="<tr class='plain' onClick=\"document.getElementById('message_id_".$row['message_id']."').checked=true;this.className='alternateover'\">\n";
					$s.="<td><input type='checkbox' name='message_id[]' value='".$row['message_id']."' id='message_id_".$row['message_id']."'></td>\n";
					$s.="<td>".$row['full_name']."</td>\n";
					$s.="<td>".$row['message']."</td>\n";
					$s.="<td>".$row['date_sent']."</td>\n";
				$s.="</tr>\n";

			}
		}
	$s.="</table>\n";
	return $s;
}

?>