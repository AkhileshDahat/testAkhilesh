<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class MobileAlert {

	function __construct() {
		$this->error="";
	}

	function SetExtra($extra) {
		$this->error.=$extra;
	}

	function ErrorID($id,$extra="") {
		$db=$GLOBALS['db'];

		$c="";
		$sql="SELECT error_id,description,popup,alert_type
		      FROM ".$GLOBALS['database_prefix']."core_error_messages
		      WHERE error_id = $id";
		//echo $sql;
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while ($row = $db->FetchArray($result)) {
				$this->error.=$row['description'];
			}
		}
	}

	function ShowError() {
		return $this->error;
	}
}

?>