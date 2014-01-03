<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/facilities/functions/browse/user_bookings.php";
require_once $GLOBALS['dr']."modules/facilities/classes/delete_booking.php";

function LoadTask() {

	$c="";
	if (ISSET($_GET['subtask'])) {
		if ($_GET['subtask']=="delete") {
			$dg=new DeleteGroup;
			$result=$dg->Remove($_SESSION['user_id'],$_GET['group_id']);
			if ($result) {
				$c.="Success";
			}
			else {
				$c.="Failure";
			}
		}
	}

	$c.=CurveBox(UserBookings($_SESSION['user_id'],$GLOBALS['ui']->WorkspaceID(),$GLOBALS['ui']->TeamspaceID()));

	return $c;
}
?>