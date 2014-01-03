<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function Alert($id,$extra="") {
	$db=$GLOBALS['db'];
	$taskbar_msg="";
	$c="";
	$sql="SELECT error_id,description,popup,alert_type
	      FROM ".$GLOBALS['database_prefix']."core_error_messages
	      WHERE error_id = $id";
	//echo $sql;
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		while ($row = $db->FetchArray($result)){
			$taskbar_msg=$row["description"];
			if ($row["popup"] == "y") {
				//echo "ok";
				if ($row['alert_type']=="info") { $button="messagebox_info"; $bg="yellow"; }
				elseif ($row['alert_type']=="error") { $button="no"; $bg="red"; }
				elseif ($row['alert_type']=="success") { $button="button_accept"; $bg="green"; }
				//$c.="<div id='greybg' name='greybg' style='background-image:url(images/transparent_spacer.gif);background-repeat:repeat;position:absolute;top:0px;left:0px;width:100%;height:100%;z-index:1;display:block'>\n";
				//$c.="</div>\n";
				$c.="<div id='alertbox' name='alertbox' style='position:absolute;left:200;top:100;z-index:2;display:block'>\n";
				$c.="<table class='alert' onClick=\"javascript:document.getElementById('alertbox').style.display='none';\">\n"; //javascript:document.getElementById('greybg').style.display='none';
					$c.="<tr>\n";
						$c.="<td valign='top'><img src='images/nuvola/32x32/actions/".$button.".png'></td>\n";
						$c.="<td>(".$row['error_id'].") ".$row['description']."<br>";
						if (!EMPTY($extra)) {
							$c.="<u>Extra: ".$extra."</u><br>";
						}
						$c.="</td>\n";
					$c.="</tr>\n";
				$c.="</table>\n";
				$c.="</div>\n";
			}
		}
	}

	$c.="<SCRIPT language='JavaScript'><!--\n";
	$c.="window.status = '".$taskbar_msg."';\n";
	$c.="//--></SCRIPT>\n";

	return $c;
}
?>