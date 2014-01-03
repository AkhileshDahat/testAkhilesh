<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function History() {

	GLOBAL $ti;

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("Description","Date","User")); /* COLS */
	$sr->Columns(array("description","date_logged","full_name"));
	$sr->Query("SELECT hth.description,hth.date_logged,um.full_name
							FROM ".$GLOBALS['database_prefix']."helpdesk_ticket_history hth, ".$GLOBALS['database_prefix']."core_user_master um
							WHERE hth.ticket_id = '".$ti->GetColVal("ticket_id")."'
							AND hth.user_id = um.user_id
							ORDER BY hth.history_id DESC
							");

	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Ticket History");
	$sr->Footer();
	return $sr->Draw();

	// I can't seem to get this to work

	GLOBAL $ti;

	$sql="SELECT hth.description,hth.date_logged,um.full_name
							FROM ".$GLOBALS['database_prefix']."helpdesk_ticket_history hth, ".$GLOBALS['database_prefix']."core_user_master um
							WHERE hth.ticket_id = '".$ti->GetColVal("ticket_id")."'
							AND hth.user_id = um.user_id
							ORDER BY hth.history_id DESC
							";
	//echo $sql;
	$sql = str_replace("\n","",$sql);
	//$sql = str_replace("  ","",$sql);
	$sql = str_replace("\t","",$sql);
	//$sql = str_replace(Chr(13),"",$sql);
	//echo $sql;
	$_SESSION['ex2'] = $sql;

	$head = $GLOBALS['head'];
	$head->IncludeFile("rico2rc2");

	$c="";

	$c.="<p class='ricoBookmark'><span id='ex2_timer' class='ricoSessionTimer'></span><span id='ex2_bookmark'>&nbsp;</span></p>\n";

	$c.="<table id='ex2' class='ricoLiveGrid' cellspacing='0' cellpadding='0'>\n";
		$c.="<colgroup>\n";
			$c.="<col style='width:350px;' >\n";
			$c.="<col style='width:40px;' >\n";
			$c.="<col style='width:50px;' >\n";
		$c.="</colgroup>\n";
	  $c.="<tr>\n";
		  $c.="<th>Description</th>\n";
		  $c.="<th>Date</th>\n";
		  $c.="<th>User</th>\n";
	  $c.="</tr>\n";
	$c.="</table>\n";


	return $c;

}
?>