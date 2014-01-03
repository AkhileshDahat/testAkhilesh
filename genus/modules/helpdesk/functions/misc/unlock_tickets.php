<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function UnlockTickets() {
	/* DATABASE CONNECTION */
	$db=$GLOBALS['db'];
	
	$sql="DELETE FROM ".$GLOBALS['database_prefix']."helpdesk_tickets_locked
				WHERE user_id = ".$_SESSION['user_id']."
				";
	//echo $sql."<br>";
	$result=$db->query($sql);
	if ($db->AffectedRows($result) > 0) {
		return True;
	}
	else {
		return False;
	}
}	