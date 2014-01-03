<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function ShowTeamSpaceModules($teamspace_id, $user_id) {
	$db=$GLOBALS['db'];
	$sql="SELECT mm.module_id, mm.name
				FROM ".$GLOBALS['database_prefix']."core_space_user_modules tum, ".$GLOBALS['database_prefix']."core_module_master mm
				WHERE tum.teamspace_id = ".$teamspace_id."
				AND tum.module_id = mm.module_id
				";
	$result = $db->Query($sql);
	$s="<table class='teamspace' cellpadding='0' cellspacing='5'>\n";
		$s.="<tr>\n";
		if ($db->NumRows($result) > 0) {
			$count=0;
			while($row = $db->FetchArray($result)) {

				$desc=STR_REPLACE("_", " ",$row['name']);
				$desc=InitCapAllWords($desc);
				if ($count > 0) {
					$s.="<td width='1' bgcolor='#BEC0CF'><img src='images/spacer.gif' width='1' height='1'></td>\n";
				}
				$s.="<td height='90' width='90' align='center' onMouseOver=\"document.getElementById('teamspace_".$row['module_id']."').className='showBlk'\" onMouseOut=\"document.getElementById('teamspace_".$row['module_id']."').className='hideBlk'\"><a href='index.php?module=".$row['name']."'>".$desc."<br><img src='modules/".$row['name']."/images/home/logo48x48.png' border='0'></a><div height='20' id='teamspace_".$row['module_id']."' class='hideBlk'><img src='images/home/teamspace_arrow_up.gif'></div></td>\n";
				$count++;
			}
			$s.="</tr>\n";
		}
	$s.="</table>\n";
	return $s;
}

function InitCapAllWords($v) {
	$first_split=explode(" ",$v);
	for($i=0;$i<count($first_split);$i++) {
		$c.=" ".STRTOUPPER(SUBSTR($first_split[$i], 0, 1)).SUBSTR($first_split[$i], 1);
	}
	return $c;
}
?>