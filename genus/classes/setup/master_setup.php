<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class MasterSetup {

	function MasterSetup($workspace_id,$teamspace_id) {

		$db=$GLOBALS['db'];

		$sql="SELECT form_dynamic_lookup
				FROM ".$GLOBALS['database_prefix']."core_space_setup
				WHERE workspace_id = ".$workspace_id."
				AND teamspace_id ".$GLOBALS['teamspace_sql']."
				";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$this->form_dynamic_lookup=$row["form_dynamic_lookup"];
			}
		}
	}
	function FormDynamicLookup() {	if ($this->form_dynamic_lookup=="y") { return True; } else { return False; } }
}
?>