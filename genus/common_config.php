<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/*** CLASS FOR SITE SETUP ***/
require_once $GLOBALS['dr']."classes/setup/setup.php";
$setup=new Setup();

global $user_id;
/* SESSION ID */
if (ISSET($_SESSION['sid'])) {

	$sid=$_SESSION['sid'];
	$user_id=$_SESSION['user_id'];
	//session_write_close();
	require_once $GLOBALS['dr']."modules/core/classes/user_info.php";
	require_once $GLOBALS['dr']."include/functions/date_time/set_timezone.php";
	$ui=new UserInfo($user_id);
	$workspace_id=$ui->WorkspaceID();
	$teamspace_id=$ui->TeamspaceID();
	//echo "Teamspace ID: ".$teamspace_id."<Br />";
	//if (EMPTY($teamspace_id)) { echo "NULL teamspace"; } else { echo "Not null teamspace"; }
	SetTimezone($ui->Timezone());
	if (EMPTY($teamspace_id)) { $teamspace_sql="IS NULL"; $teamspace_id="NULL"; } else { $teamspace_sql="= $teamspace_id"; }

	/* GET THE WORKSPACE DATA */

	require_once($dr."modules/core/classes/workspace_id.php");
	$obj_wi=new WorkspaceID;
	$obj_wi->SetParameters($workspace_id);

}

/* CREATE AN INSTANCE OF THE WORKSPACE */
if (ISSET($_SESSION['sid']) && IS_NUMERIC($ui->WorkspaceID()) && $ui->WorkspaceID() > 0) {
	require_once $GLOBALS['dr']."modules/core/classes/workspace_user_info.php";
	$wui=new WorkspaceUserInfo($_SESSION['user_id'],$ui->WorkspaceID());
}


/* JPGRAPH SETTINGS */
$jpgraph_font_dir=$dr."include/jpgraph/fonts/";

/* LOGGING */
require_once $dr."modules/core/functions/log/core_log.php";
CoreLog();

/* LANGUAGE */
if (ISSET($_SESSION['sid']) && strlen($ui->GetInfo("language"))>0) {
	$default_lang=$ui->GetInfo("language");
}
//elseif (ISSET($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
	//$lang=$_SERVER['HTTP_ACCEPT_LANGUAGE'];
	//$arr_lang=explode(",",$lang);
	//$default_lang=$arr_lang[0];
//print_r($arr_lang);
//}
else {
	$default_lang="en";
}

if (file_exists($GLOBALS['dr']."language/".$default_lang.".php")) {
	require_once $dr."language/".$default_lang.".php";
}
else {
	require_once $dr."language/en-us.php";
}
?>