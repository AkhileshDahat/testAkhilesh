<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/documents/classes/category_id.php";

function LoadTask() {

	GLOBAL $document_id,$did;

	$category_id=EscapeData($_GET['category_id']);

	if (ISSET($_GET['confirm']))  {
		$category_id=$_GET['category_id'];
		$cid=new CategoryID();
		$cid->SetParameters($category_id);
		$cid->Delete();
		header("Location: index.php?module=documents&task=home");
	}

	$c="Are you sure you want to delete this folder?<br />";
	$c.="<input type=button onClick=\"document.location.href='index.php?module=documents&task=confirm_folder_delete&category_id=$category_id&confirm'\" value='YES'>";
	$c.="<input type=button onClick=\"document.location.href='index.php?module=documents&task=home&category_id=$category_id'\" value='NO'>";


	return $c;
}
?>