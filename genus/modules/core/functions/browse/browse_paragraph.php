<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function BrowseParagraph($content_category_name) {

	$db=$GLOBALS['db'];
	$sql="SELECT content_data
				FROM ".$GLOBALS['database_prefix']."core_content_master
				WHERE content_category_name = '".strtolower($content_category_name)."'
				";
	//echo $sql;
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		while($row = $db->FetchArray($result)) {
			return $row['content_data'];
		}
	}



}

?>