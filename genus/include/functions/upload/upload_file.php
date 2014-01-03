<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function UploadFile($fieldname,$upload_dir,$upload_dir_file) {

	$upload_file = $_FILES[$fieldname]['name'];
	$file_size=$_FILES[$fieldname]['size'];
	$file_type=$_FILES[$fieldname]['type'];

	############################################################
	#	MOVE THE FILE TO THE INTENDED DESTINATION AND STORE IN DB
	############################################################
	if (move_uploaded_file($_FILES[$fieldname]['tmp_name'], $upload_dir_file)) {
		return True;
	}
	else {
		return False;
	}
}

?>