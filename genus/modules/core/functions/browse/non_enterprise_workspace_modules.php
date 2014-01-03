<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."modules/core/functions/acl/workspace_module_role_exists.php";

function NonEnterpriseModules($workspace_id,$user_id,$role_id) {

	$db=$GLOBALS['db']; /* DATABASE CONNECTION */
	$wb=$GLOBALS['wb']; /* WEBSITE URL */

	if (!defined( '_VALID_MVH_MOBILE_' )) {
		$break_after=4;
		$mobile=false;
	}
	else {
		$break_after=2;
		$mobile=true;
	}
	if ($GLOBALS['teamspace_id'] == 0) {
		$header="Workspace";
		$sql="SELECT mm.module_id, lower(mm.name) as name, mm.logo
					FROM ".$GLOBALS['database_prefix']."core_space_user_modules wum, ".$GLOBALS['database_prefix']."core_module_master mm
					WHERE wum.workspace_id = ".$workspace_id."
					AND wum.user_id = ".$_SESSION['user_id']."
					AND wum.module_id = mm.module_id
					ORDER BY wum.ordering
					";
	}
	else {
		$header="Teamspace";
		$sql="SELECT mm.module_id, lower(mm.name) as name, mm.logo
					FROM ".$GLOBALS['database_prefix']."core_space_user_modules tum, ".$GLOBALS['database_prefix']."core_module_master mm
					WHERE tum.teamspace_id = ".$GLOBALS['teamspace_id']."
					AND tum.user_id = ".$_SESSION['user_id']."
					AND tum.module_id = mm.module_id
					ORDER BY tum.ordering
					";
	}
	//echo $sql."<br>";
	$result = $db->Query($sql);
	$s="<table class='plain' bgcolor='#F7F8FB' cellpadding='0' cellspacing='0' align='center' valign='top'>\n";
		$s.="<tr>\n";
			$s.="<td colspan='3' bgcolor='#E4E9F4' class='colhead' align='center'>Your ".$header."</td>\n";
		$s.="</tr>\n";
		$s.="<tr>\n";
			$s.="<td>\n";
			$s.="<table class='teamspace' cellpadding='0' cellspacing='5'>\n";
				if ($db->NumRows($result) > 0) {
					$count=0;
					$count_t=0;
					while($row = $db->FetchArray($result)) {
						if (WorkspaceModuleRoleExists($workspace_id,$row['module_id'],$GLOBALS['wui']->RoleID())) { /* CHECK THE ROLE FOR THE MODULE */
							$count_t++;
							if ($count==0) {
								$s.="<tr>\n";
							}
							$desc=STR_REPLACE("_", " ",$row['name']);
							$desc=InitCapAllWords($desc);
							if ($count > 0) {
								$s.="<td width='1' bgcolor='#BEC0CF'><img src='images/spacer.gif' width='1' height='1'></td>\n";
							}
							if (!$mobile) {
								//$s.="<td height='90' width='90' align='center' onMouseOver=\"document.getElementById('teamspace_".$row['module_id']."').className='showBlk'\" onMouseOut=\"document.getElementById('teamspace_".$row['module_id']."').className='hideBlk'\"><a href='index.php?module=".$row['name']."&task=home'>".$desc."<br><img src='".$wb."images/".$row['logo']."' border='0'></a><div height='20' id='teamspace_".$row['module_id']."' class='hideBlk'><img src='".$wb."images/home/teamspace_arrow_up.gif'></div></td>\n";
								$s.="<td height='90' width='90' align='center' onMouseOver=\"document.getElementById('teamspace_".$row['module_id']."').className='showBlk'\" onMouseOut=\"document.getElementById('teamspace_".$row['module_id']."').className='hideBlk'\"><a href='index.php?module=".STRTOLOWER($row['name'])."&task=home'>".$desc."<br><img src='modules/".strtolower($row['name'])."/images/".$GLOBALS['wui']->Theme()."/icon_module.png' border='0'></a><div height='20' id='teamspace_".$row['module_id']."' class='hideBlk'><img src='".$wb."images/home/teamspace_arrow_up.gif'></div></td>\n";
							}
							else {
								$s.="<td align='center' onMouseOver=\"document.getElementById('teamspace_".$row['module_id']."').className='showBlk'\" onMouseOut=\"document.getElementById('teamspace_".$row['module_id']."').className='hideBlk'\"><a href='index.php?module=".$row['name']."&task=home'>".$desc."</a></td>\n";
							}
							$count++;
							if ($count==$break_after) {
								$s.="</tr>\n";
								$count=0;
							}
						}
						// USE THESE LINES FOR DEBUGGING ONLY - NEVER ENABLE THEM AS THE USR MUST NOT SEE WHAT THEY ARE DENIED
						/*
						else {
							$s.="<tr><td>Role Denied - ".$row['name']." - ".$row['module_id']."</td></tr>";
						}
						*/
					}
				}
			$s.="</table>\n";
			$s.="</td>\n";
		$s.="</tr>\n";
	$s.="</table>\n";
	return $s;
}

function InitCapAllWords($v) {
	$first_split=explode(" ",$v);
	$c="";
	for($i=0;$i<count($first_split);$i++) {
		$c.=" ".STRTOUPPER(SUBSTR($first_split[$i], 0, 1)).SUBSTR($first_split[$i], 1);
	}
	return $c;
}
?>