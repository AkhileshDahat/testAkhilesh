<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function SessionOpen($save_path,$session_name) {
	//echo "Session Open<br />";
	//echo "Set db in session<br />";
	//session_register("db");

	return True;
}

function SessionClose() {

	//SessionCleanUp(); /* REMOVES ALL EXPIRED SESSIONS*/
}

function SessionCleanUp() {
	$sql="DELETE FROM ".$GLOBALS['database_prefix']."core_sessions WHERE session_time < ".(time()-(3600));
	//echo $sql."<br />";
	$GLOBALS['db']->query($sql);
}

function SessionRead($session_id) {

	//echo "ok1";
	$db=$GLOBALS['db'];
  $sql="SELECT session_data
   				FROM ".$GLOBALS['database_prefix']."core_sessions
   				WHERE session_id='".$session_id."'
   				";
  //echo $sql."0<br />";
  $result = $db->Query($sql);

  if ($db->NumRows($result) > 0) {
		//echo "found data 1<br />";
  	while($row = $GLOBALS['db']->FetchArray($result)) {
			//echo "found data<br />";
  		return $row['session_data'];
  	}
  }
  else {
		//echo "found no data<br />";
  	$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_sessions (session_id, session_time, session_data)
            VALUES (
            '".$session_id."',
            now(),
            ''
            )";
    //echo $sql."<br />";
		$GLOBALS['db']->query($sql);
    return "";
  }
}
/* ACCORDING TO THE MANUAL WE CAN'T USE GLOBAL VARIABLES IN HERE SINCE 5.0.5 */
function SessionWrite($session_id,$session_data) {
	//if (ISSET($_SESSION['db'])) {	echo "Database Connection Exists<br />"; } else { echo "No Connection<br />"; }
	//DoSessionWrite($session_id,$session_data);

	//$db=$GLOBALS['db'];
	mysql_connect("localhost","root","rootroot");
	mysql_select_db("genus");
	$session_data=addslashes($session_data);
  $sql="UPDATE ".$GLOBALS['database_prefix']."core_sessions
  				SET session_data = '$session_data',
  				session_time = now()
  				WHERE session_id = '".$session_id."'
  				";
  //echo $sql."<br />";
	$result=mysql_query($sql);
	if (mysql_affected_rows() > 0) {
		//echo "success";
		return True;
	}
	else {
		//echo "error setting session";
		return False;
	}

}

function DoSessionWrite($session_id,$session_data) {

}


function SessionDestroy($session_id) {
	$sql = "DELETE FROM ".$GLOBALS['database_prefix']."core_sessions WHERE session_id = '".$session_id."'";
  //echo $sql."<br />";
	$GLOBALS['db']->query($sql);
  return True;
}
?>