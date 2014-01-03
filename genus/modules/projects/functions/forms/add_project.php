<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function AddProject($project_id="",$project_code="",$title="",$description="",$category_id="",$status_id="",$priority_id="",$start_date="",$estimated_completion_date="",
										$actual_completion_date="",$estimated_cost="",$percentage_completion="") {
		
	/* INCLUDE THE JCALENDAR */
	$GLOBALS['head']->IncludeFile("jscalendar");
	
	$c="";	
	
	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("send_sms");
	$fv->OpenTag();
	$fv->ValidateFields("account_name");
	$fv->CloseTag();
	$c.=$fv->Draw();
	
	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=projects&task=add","post","add_project","onSubmit=\"ValidateForm(this)\"");
	$form->Header("../modules/projects/images/default/icon_module.png","Add a new project");
	
	$form->Input("Code","project_code","","","","","10");
	$form->Input("Title","title","","","","","40");
	$form->Textarea("Description","description",5,60,$description);	
	$form->ShowDropDown("Category","category_id","category_name","project_category_master","category_id",$category_id,"","WHERE workspace_id = ".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'],"","input_reqd");	
	
	$form->ShowDropDown("Status","status_id","status_name","project_status_master","status_id",$status_id,"","WHERE workspace_id = ".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'],"","input_reqd");	
	$form->ShowDropDown("Priority","priority_id","priority_name","project_priority_master","priority_id",$priority_id,"","WHERE workspace_id = ".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'],"","input_reqd");	
	
	$form->BreakCell("Project Dates");
	$form->Date("Start","start_date",$start_date,"");
	$form->Date("Est Complete","estimated_completion_date",$estimated_completion_date,"");
	$form->Date("Act Complete","actual_completion_date",$actual_completion_date,"");
	
	$form->BreakCell("Costing");
	$form->Input("Est Cost","estimated_cost","","","","","10");
	$form->Input("Act Cost","actual_cost","","","","","10");
	
	$form->BreakCell("Completion");
	$form->Input("% Complete","percentage_completion","","","","","3");
	$c.=$form->DrawForm();
	
	
	/* FOCUS ON THE INVOICE NUMBER FIELD */
	$c.="<script language='JavaScript'>\n";
	$c.="document.add_project.project_code.focus();\n";
	$c.="</script>\n";
	return $c;
}
?>