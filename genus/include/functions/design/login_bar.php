<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function LoginBar($mobile=False) {
	$ui=$GLOBALS['ui'];
	$obj_wi=$GLOBALS['obj_wi'];
	$workspace_id=$ui->WorkspaceID();
	$teamspace_id=$ui->TeamspaceID();
	$s="<ul id=\"tablist\">\n";
		$s.="<li><a href=\"#\" accesskey=\"b\"><span class=\"key\">B</span>logs</a></li><li><a href=\"#\" accesskey=\"p\"><span class=\"key\">P</span>hotos</a></li><li><a href=\"#\" accesskey=\"r\">P<span class=\"key\">r</span>ofiles</a></li><li><a href=\"#\" accesskey=\"f\"><span class=\"key\">F</span>eeds</a></li><li><a href=\"#\" accesskey=\"o\">Br<span class=\"key\">o</span>adcast News</a></li>";

			if ($mobile==False) {
				$s.="<td> </td>\n";
				$s.="<li>"._WELCOME_."<a class=\"current\" href=\"#\" accesskey=\"n\"><span class=\"key\">".$ui->FullName()."</span></a></li>\n";
			}
			$s.="<td width=60%><a href='index.php'>"._HOME_."</a> | ";
			if ($GLOBALS['use_wiki_help']) {
				$s.="<a href='".GetWikiUrlHelp()."' target='new'>"._HELP_."</a> | ";
			}
			/* SHOW THE DASHBOARD LINK */
			if ($obj_wi->GetColVal("enterprise")=="y") {
				if ($GLOBALS['ui']->GetInfo("show_dashboard")=="y") {
					$s.="<a href='index.php?dtask=show_dashboard&do=n'>Classic</a> | ";
					}
					else {
						$s.="<a href='index.php?dtask=show_dashboard&do=y'>Dashboard</a> | ";
					}
				}
			//if (!EMPTY($workspace_id) && $mobile==False) {
				//$s.="You are logged into: <a href='bin/workspace/unset.php'>".GetColumnValue("name","core_workspace_master","workspace_id",$workspace_id)."</a>";
			//}
			if (!EMPTY($teamspace_id)) {
				$s.="<img src='images/nuvola/16x16/actions/forward.png' height='16' width='16'><a href='index.php?dtask=deactivate_teamspace'>".GetColumnValue("name","core_teamspace_master","teamspace_id",$teamspace_id)."</a>";
			}
			$s.="</td>\n";
			if (!EMPTY($workspace_id)) {
				$s.="<form method='post' action='index.php?dtask=deactivate_workspace'><td><input type='submit' value='"._LOGOUT_WORKSPACE_."' class='buttonstyle'></td></form>\n";
			}
			$s.="<form method='post' action='index.php?dtask=logout'><td><input type='submit' value='"._LOGOUT_."' class='buttonstyle'></td></form>\n";
		$s.="</tr>\n";
	$s.="</ul>\n";
	return $s;
}

function GetWikiUrlHelp() {
	$v=$GLOBALS['wiki_url'];
	$v.="doku.php?id=";
	$v.="lams";
	if (ISSET($_GET['module'])) {
		$v.=":".$_GET['module'];
	}
	if (ISSET($_GET['task']) && $_GET['task'] != "home" && !EMPTY($_GET['task'])) {
		$v.=":".$_GET['task'];
	}
	return $v;
}
?>