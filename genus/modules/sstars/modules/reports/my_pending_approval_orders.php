<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."modules/sstars/dashboard/pending_approval.php";

echo PendingApproval($user_id);

?>