<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function SubjectDetail($subject_detail_id="",$subject_id="",$user_id="",$duration_hours="",$date_start="",$date_end="",$capacity="",$description="") {

	$c="";

	$GLOBALS['head']->IncludeFile("jscalendar");

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("categories");
	$fv->OpenTag();
	$fv->ValidateFields("subject_name");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=scheduling&task=subject_detail","post","subjects","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Master Subjects");

	$form->Hidden("subject_detail_id",$subject_detail_id);

	$form->ShowDropDown("Subject","subject_id","subject_name","scheduling_subject_master","subject_id",$subject_id,"","WHERE workspace_id = ".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'],"","input_reqd");
	$form->ShowDropDown("Lecturer's Name","user_id","full_name","core_user_master","user_id",$user_id,"","WHERE workspace_id = ".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'],"","input_reqd");

	$form->Input("Duration (Hours)","duration_hours","","","",$duration_hours);
	$form->Date("Start Date","date_start",$date_start);
	$form->Date("End Date","date_end",$date_end);
	$form->Input("Capacity","capacity","","","",$capacity);
	$form->Input("Description","description","","","",$description);

	$form->CheckboxDBMultiple("Facilities required:","item_id","item_name","resource_item_master","resource_item_reqs","","","WHERE workspace_id = ".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'],"","input_reqd");

	//$form->SetFocus("subject_name");

	$c.=$form->DrawForm();

	return $c;
}
?>