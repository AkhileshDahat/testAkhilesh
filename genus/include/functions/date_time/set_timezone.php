<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function SetTimezone($t) {
 	if ($GLOBALS['database_type'] == "postgres") {
		$db=$GLOBALS['db'];
		$sql="SET timezone = '".$t."'";
		$result = $db->Query($sql);
	}
}

?>