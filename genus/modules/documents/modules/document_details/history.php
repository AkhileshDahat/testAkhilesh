<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function History() {

	GLOBAL $document_id;
	GLOBAL $did;

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("Description","Date","User")); /* COLS */
	$sr->Columns(array("description","date_logged","full_name"));
	$sr->Query("SELECT dfh.description,dfh.date_logged,um.full_name
							FROM ".$GLOBALS['database_prefix']."document_file_history dfh, ".$GLOBALS['database_prefix']."core_user_master um
							WHERE dfh.filename = '".$did->GetColVal("filename")."'
							AND dfh.category_id = ".$did->GetColVal("category_id")."
							AND dfh.user_id = um.user_id
							ORDER BY dfh.date_logged DESC
							");

	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Document History");
	return $sr->Draw();

}
?>