<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class SSTARSUserSettings {

	function SSTARSUserSettings($user_id) {

		$db=$GLOBALS['db'];

		$sql="SELECT col_owner, col_rating, col_size
					FROM ".$GLOBALS['database_prefix']."document_user_settings
					WHERE user_id = '".$user_id."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$this->col_owner=$row["col_owner"];
				$this->col_rating=$row["col_rating"];
				$this->col_size=$row["col_size"];
			}
		}
	}
	function ColOwner() {	return $this->col_owner; }
	function ColRating() {	return $this->col_rating; }
	function ColSize() {	return $this->col_size; }
}
?>