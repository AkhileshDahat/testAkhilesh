<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function CategoryMaxDocumentIDS($category_id) {

	$db=$GLOBALS['db'];

	$document_id_in="";

	$sql="SELECT MAX(document_id) as document_id
				FROM ".$GLOBALS['database_prefix']."document_files
				WHERE category_id = '".$category_id."'
				GROUP BY filename
				";
	//echo $sql;
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		$count=0;
		while($row = $db->FetchArray($result)) {
			$count++;
			$document_id_in.="'".$row['document_id']."'";
			if ($count<$db->NumRows($result)) {
				$document_id_in.=",";
			}
		}
		unset($count);
	}
	return $document_id_in;
}
?>