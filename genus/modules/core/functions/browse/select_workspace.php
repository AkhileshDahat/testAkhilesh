<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/date_time/mysql_to_seconds.php";

function SelectWorkspace() {
	$db=$GLOBALS['db'];
	$wb=$GLOBALS['wb'];
	$sql="SELECT wm.workspace_id, wm.workspace_code, wm.logo, wm.date_start, wm.date_end, csu.approved
				FROM ".$GLOBALS['database_prefix']."core_space_users csu, ".$GLOBALS['database_prefix']."core_workspace_master wm
				WHERE wm.date_start <= curdate()
				AND wm.date_end >= curdate()
				AND csu.user_id = ".$_SESSION['user_id']."
				AND csu.workspace_id = wm.workspace_id
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	$s="<table class='plain' width='100%' bgcolor='#F7F8FB' border='0' cellpadding='0' cellspacing='0' align='center' valign='top'>\n";
		$s.="<tr>\n";
			$s.="<td colspan='3' bgcolor='#E4E9F4' class='colhead' align='center'>"._SELECT_WORKSPACE_."</td>\n";
		$s.="</tr>\n";
		$s.="<tr>\n";
			$s.="<td>\n";
			$s.="<table class='teamspace' width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
				$s.="<tr>\n";
				if ($db->NumRows($result) > 0) {
					$count=0;
					while($row = $db->FetchArray($result)) {
						if ($count > 0) {
							$s.="<td width='1' bgcolor='#BEC0CF'><img src='".$GLOBALS['wb']."images/spacer.gif' width='1' height='1'></td>\n";
						}
						if ($row['approved']=="y") {
							//echo $row['logo'];
							$s.="<td height='90' width='90' align='center' onMouseOver=\"document.getElementById('teamspace_".$row['workspace_id']."').className='showBlk'\" onMouseOut=\"document.getElementById('teamspace_".$row['workspace_id']."').className='hideBlk'\"><a href='index.php?dtask=activate_workspace&workspace_id=".$row['workspace_id']."'>".$row['workspace_code']."<br><img src='".$wb."images/workspaces/".$row['logo']."' border='0'></a><div height='20' id='teamspace_".$row['workspace_id']."' class='hideBlk'><img src='".$wb."images/home/teamspace_arrow_up.gif'></div></td>\n";
						}
						else {
							$s.="<td height='90' width='90' align='center'>".$row['workspace_code']."<br><img src='".$wb."images".$row['logo']."' border='0'><br>"._WORKSPACE_PENDING_APPROVAL_."</td>\n";
						}
						$count++;
					}
					$s.="</tr>\n";
				}
			$s.="</table>\n";
			$s.="</td>\n";
		$s.="</tr>\n";
	$s.="</table>\n";
	return $s;
}
?>