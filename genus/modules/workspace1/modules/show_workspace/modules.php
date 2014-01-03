<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."include/functions/db/row_exists.php";
require_once $dr."modules/workspace/classes/module.php";

if ($_GET['add_rem_module_id']) {
	$mod=new Module;
	$mod->SetParameters(EscapeData($_GET['workspace_id']),EscapeData($_GET['add_rem_module_id']));
	if ($mod->AddRemoveModule()) {
		echo Alert(35);
	}
	else {
		echo Alert(34,$mod->ShowErrors());
	}
}


echo Modules(EscapeData($_GET['workspace_id']));

function Modules($workspace_id) {
	$db=$GLOBALS['db'];

	$sql="SELECT wm.module_id, mm.name
				FROM ".$GLOBALS['database_prefix']."core_space_modules wm, ".$GLOBALS['database_prefix']."core_module_master mm
				WHERE wm.module_id = mm.module_id
				ORDER BY mm.name
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	$s="<table class='plain_border'>\n";
		$s.="<tr>\n";
			$s.="<td class='colhead' colspan='10'>Modules</td>\n";
		$s.="</tr>\n";
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				if (RowExists("workspace_modules","module_id",$row['module_id'],"AND workspace_id = '".$workspace_id."'")) {
					$module_exists="Yes";
				}
				else {
					$module_exists="No";
				}
				$s.="<tr onmouseover=\"this.className='alternateover'\" onMouseOut=\"this.className=''\">\n";
					$s.="<td class='bold'><a href='index.php?module=workspace&task=show_workspace&workspace_id=".$workspace_id."&module_id=".$row['module_id']."&block_show=roles' title='Click to view role access'>".$row['name']."</a></td>\n";
					$s.="<td><a href='index.php?module=workspace&task=show_workspace&workspace_id=".$workspace_id."&add_rem_module_id=".$row['module_id']."&block_show=modules'>".$module_exists."</a></td>\n";
				$s.="</tr>\n";
			}
		}
	$s.="</table>\n";

	return $s;
}
?>