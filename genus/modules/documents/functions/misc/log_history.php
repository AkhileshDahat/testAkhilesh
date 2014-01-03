<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function LogDocumentHistory($user_id,$description) {
	$db=$GLOBALS['db'];
	$sql="INSERT INTO ".$GLOBALS['database_prefix']."document_history
				(user_id, description, log_date)
				VALUES (
				'".$user_id."',
				'".$description."',
				sysdate()
				)";
	//echo $sql;
	$result=$db->query($sql);
	if ($result) {
		return True;
	}
	else {
		return False;
	}
}

function LogDocumentFileHistory($filename,$category_id,$version_number,$description) {
	$db=$GLOBALS['db'];
	$sql="INSERT INTO ".$GLOBALS['database_prefix']."document_file_history
				(filename, category_id, version_number, workspace_id, teamspace_id, user_id, description, date_logged)
				VALUES (
				'".$filename."',
				'".$category_id."',
				'".$version_number."',
				'".$GLOBALS['workspace_id']."',
				'".$GLOBALS['teamspace_id']."',
				'".$_SESSION['user_id']."',
				'".$description."',
				now()
				)";
	//echo $sql;
	$result=$db->query($sql);
	if ($result) {
		return True;
	}
	else {
		return False;
	}
}
?>