<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."classes/design/tab_boxes.php";

require_once $GLOBALS['dr']."modules/documents/classes/document_id.php";

function LoadTask() {

	GLOBAL $document_id,$did;
	$document_id=$_GET['document_id'];

	$did=new DocumentID();
	$did->SetParameters($document_id);

	$c="";
	$tab_array=array("summary","history");
	$tb=new TabBoxes;
	$c.=$tb->DrawBoxes($tab_array,$GLOBALS['dr']."modules/documents/modules/document_details/");

	return $c;
}
?>