<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."classes/design/tab_boxes.php";
require_once $GLOBALS['dr']."modules/helpdesk/classes/plugins.php";
require_once $GLOBALS['dr']."modules/helpdesk/classes/ticket_id.php";

function LoadTask() {

	$c="";
	GLOBAL $obj_ti;

	// PROCESS THE TICKET ID TO A SESSION
	$ticket_id = "";
	if (ISSET($_GET['ticket_id'])) {
		$_SESSION['ticket_id'] = htmlentities($_GET['ticket_id']);
	}
	if (ISSET($_SESSION['ticket_id'])) {
		$ticket_id = $_SESSION['ticket_id'];
	}
	// SEARCH FORM
	$c.="<table border=0 cellpadding=0 cellspacing=0 class=plain1>\n";
		$c.="<form method=get action=index.php>\n";
		$c.="<input type=hidden name=module value=helpdesk>\n";
		$c.="<input type=hidden name=task value=ticket_plugins>\n";
		//$c.="<input type=hidden name=plugin value=find>\n";
		//$c.="<input type=hidden name=plugin_menu value=Display>\n";
		$c.="<tr>\n";
			$c.="<td>Enter Ticket ID:</td>\n";
			$c.="<td><input type=text name=ticket_id size=5 value=$ticket_id></td>\n";
			$c.="<td><input type=submit name=submit value=Find></td>\n";
		$c.="</tr>\n";
		$c.="</form>\n";
	$c.="</table>\n";

	if (!ISSET($_SESSION['ticket_id'])) { return $c; }

	// PLUGINS
	GLOBAL $ti;
	$ti = new TicketID;
	$ti->SetParameters($_SESSION['ticket_id']);

	$obj_plugins = new plugins;
	$arr_menu = $obj_plugins->GetMenus();
	$arr_plugins = $obj_plugins->GetMenuOrderPlugins();

	//sort($arr_menu);
	//print_r($arr_menu);
	//echo count($arr_menu);
	for ($i=0;$i<count($arr_menu);$i++) {
		$c .= "<div class=pluginmenu><a href='index.php?module=helpdesk&task=ticket_plugins&plugin=".$arr_plugins[$i]."&plugin_menu=".$arr_menu[$i]."'>".$arr_menu[$i]."</a></div>\n";
	}
	$c .= "<br />\n";
	$c .= "<br />\n";
	if (ISSET($_GET['plugin']) && ISSET($_GET['plugin_menu'])) {
		$c .= "<div>\n";
		$c .= $obj_plugins->GetMenuContent($_GET['plugin'],$_GET['plugin_menu']);
		$c .= "</div>\n";
	}

	return $c;

}

?>