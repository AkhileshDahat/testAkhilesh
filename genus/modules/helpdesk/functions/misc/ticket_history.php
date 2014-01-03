<?php
function TicketHistory($ticket_id,$description) {
	/* DATABASE CONNECTION */
	$db=$GLOBALS['db'];

	$sql="INSERT INTO ".$GLOBALS['database_prefix']."helpdesk_ticket_history
				(ticket_id,description,user_id,date_logged)
				VALUES (
				".$ticket_id.",
				'".EscapeData($description)."',
				".$_SESSION['user_id'].",
				sysdate()
				)";
	//echo $sql."<br>";
	$result=$db->query($sql);
	if ($db->AffectedRows($result) > 0) {
		return True;
	}
	else {
		return False;
	}
}
?>