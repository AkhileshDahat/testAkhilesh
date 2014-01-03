<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/*
	INCLUDE FILES REQUIRED
*/

require_once $GLOBALS['dr']."classes/form/create_form.php";

//require_once $GLOBALS['dr']."modules/documents/classes/add_category.php";
require_once $GLOBALS['dr']."classes/form/form_validate.php";
require_once $GLOBALS['dr']."include/functions/forms/create_fields.php";
//require_once $GLOBALS['dr']."include/functions/db/row_exists.php";

require_once $GLOBALS['dr']."modules/documents/classes/category_id.php";

function LoadTask($category_name="") {

	$ui=$GLOBALS['ui'];

	$c="";

	/* FORM PROCESSING */
	if (ISSET($_POST['category_name'])) {
		$category_id=EscapeData($_POST['category_id']);

		$ci=new CategoryID;
		$result_add=$ci->Add($_POST['category_id'],$_POST['category_name']);
		if (!$result_add) {
			$c.="Failed";
			$c.=$ci->ShowErrors();
		}
		else {
			$c.="Success";
		}
	}

	/* FORM VALIDATION */
	$fv=new FieldValidation;
	$fv->OpenTag();
	$fv->ValidateFields("category_name");
	$fv->CloseTag();

	/* DESIGN THE FORM */

	$form=new CreateForm;
	$form->SetCredentials("index.php?module=documents&task=add_category&category_id=".EscapeData($_GET['category_id']),"post","document_settings","onSubmit=\"ValidateForm(this)\"");
	//$form->Header("crystalclear/48x48/apps/access.png",_DOCUMENTS_ADD_FOLDER_TITLE_);

	$form->Hidden("category_id",EscapeData($_GET['category_id']));
	$form->BreakCell("Add new folder");
	$form->Input("Folder Name","category_name");
	$form->InformationCell("Folder names cannot be duplicated in each category");

	$form->SetFocus("category_name");

	$c.=$form->DrawForm();
	return $c;
}
?>