<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/messaging/functions/browse/messages.php";

function LoadTask() {

	if (ISSET($_GET['limit']) && IS_NUMERIC($_GET['limit'])) { $limit=$_GET['limit']; } else { $limit=20; }
	if (ISSET($_GET['offset']) && IS_NUMERIC($_GET['offset'])) { $offset=$_GET['offset']; } else { $offset=0; }

	if (ISSET($_GET['box'])) { $box=$_GET['box']; } else { $box=""; }
	return Messages($box,$limit,$offset);
}
?>