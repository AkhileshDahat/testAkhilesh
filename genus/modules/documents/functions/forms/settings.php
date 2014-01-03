<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";

function Settings($show_document_rating,$enable_document_encryption,$enable_anonymous_document_links,$theme) {

	$c="";

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=documents&task=settings","post","document_settings","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Settings");

	$form->BreakCell("Document Information");
	$form->Checkbox("Show document rating","show_document_rating",$show_document_rating);
	$form->InformationCell("Note: This can affect system performance.");
	$form->Checkbox("Enable document encryption","enable_document_encryption",$enable_document_encryption);
	$form->WarningCell("Not implemented! (All documents uploaded are encrypted using your password which you have used to login to this session with.");
	$form->Checkbox("Enable anonymous document links","enable_anonymous_document_links",$enable_anonymous_document_links);
	$form->InformationCell("All documents have a unique URL to make them available to download by anonymous users. Note that the URL is the key to accessing the document");

	$form->BreakCell("Theme");
	$form->ShowDropDown("Theme","theme","theme","document_theme_master","theme",$theme);

	//$form->Submit("Save","FormSubmit");

	$c.=$form->DrawForm();

	return $c;
}
?>