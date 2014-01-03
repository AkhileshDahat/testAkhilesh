<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/documents/functions/forms/settings.php";
//require_once $GLOBALS['dr']."modules/crm/classes/contact_id.php";

function LoadTask() {

	$ui=$GLOBALS['ui'];
	$ds=$GLOBALS['ds'];

	$c="";

	/* FORM PROCESSING */
	if (ISSET($_POST['FormSubmit'])) {

		/* CHECKBOX CHECKING */
		if (ISSET($_POST['show_document_rating']) && $_POST['show_document_rating']=="y") { $v_show_document_rating="y"; } else { $v_show_document_rating="n"; }
		if (ISSET($_POST['enable_document_encryption']) && $_POST['enable_document_encryption']=="y") { $v_enable_document_encryption="y"; } else { $v_enable_document_encryption="n"; }
		if (ISSET($_POST['enable_anonymous_document_links']) && $_POST['enable_anonymous_document_links']=="y") { $v_enable_anonymous_document_links="y"; } else { $v_enable_anonymous_document_links="n"; }
		$theme=$_POST['theme'];

		/* CALL THE METHOD TO EDIT */
		$result_add=$ds->Edit($v_show_document_rating,$v_enable_document_encryption,$v_enable_anonymous_document_links,$theme);
		if (!$result_add) {
			$c.="Failed";
			$c.=$ds_mod->ShowErrors();
		}
		else {
			$c.="Success";
		}

		/* REFRESH THE OBJECT AFTER THE CHANGES */
		$ds=new DocumentSetup();
	}

	/*
		DESIGN THE FORM
	*/
	$c.=Settings($ds->GetInfo("show_document_rating"),$ds->GetInfo("enable_document_encryption"),$ds->GetInfo("enable_anonymous_document_links"),$ds->GetInfo("theme"));

	return $c;
}
?>