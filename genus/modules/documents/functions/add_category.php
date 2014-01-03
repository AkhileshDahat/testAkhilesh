<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function AddCategory($parent_id,$category_name,$workspace_id,$teamspace_id) {
	$db=$GLOBALS['db'];
	$sql="INSERT INTO ".$GLOBALS['database_prefix']."document_categories
				(parent_id, category_name, workspace_id, teamspace_id)
				VALUES (
				".$parent_id.",
				'".$category_name."',
				".$workspace_id.",
				".$teamspace_id."
				)";
	$result=$db->query($sql);
	if ($result) {
		return True;
	}
	else {
		return False;
	}
}
?>