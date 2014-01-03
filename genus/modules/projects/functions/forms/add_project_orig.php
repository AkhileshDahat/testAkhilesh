<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/forms/create_fields.php";
//require_once $GLOBALS['dr']."include/functions/db/get_col_value.php";
require_once $GLOBALS['dr']."include/functions/forms/dynamic_dropdown.php";
require_once $GLOBALS['dr']."classes/form/form_validate.php";
require_once $GLOBALS['dr']."include/functions/misc/jcalendar.php";

//require_once $GLOBALS['dr']."modules/projects/classes/new_project.php";

function AddProject($project_id="",$project_code="",$title="",$description="",$category_id="",$status_id="",$priority_id="",$start_date="",$estimated_completion_date="",
										$estimated_cost="",$percentage_completion="") {
	$ui=$GLOBALS['ui'];
	
	$c="";
	
	$show_form=True;
	$show_submit="";
	/*
		THE FORM IS BEING SUBMITTED
	*/
	if (ISSET($_POST['add_project'])) {
		$err=False; /* ASSUME NO ERRORS AS WE GO ALONG */
		//$c.="Saving...<br>";
		$project_id=EscapeData($_POST['project_id']);
		$project_code=EscapeData($_POST['project_code']);
		$title=EscapeData($_POST['title']);
		$description=EscapeData($_POST['description']);
		$category_id=EscapeData($_POST['category_id']);
		$status_id=EscapeData($_POST['status_id']);
		$priority_id=EscapeData($_POST['priority_id']);
		$start_date=EscapeData($_POST['start_date']);
		$estimated_completion_date=EscapeData($_POST['estimated_completion_date']);
		$estimated_cost=EscapeData($_POST['estimated_cost']);
		$percentage_completion=EscapeData($_POST['percentage_completion']);

		$p=new NewProject; /* OBJECT TO CREATE A NEW BATCH */
		/* IF THERE'S NO BATCH ID THEN WE'RE INSERTING A NEW RECORD */
		if (!$_POST['project_id']) {
			$result=$p->AddProject($project_code,$title,$description,$category_id,$status_id,$priority_id,$start_date,
							$estimated_completion_date,$estimated_cost,$percentage_completion,$ui->WorkspaceID(),$ui->TeamspaceID());
			if ($result) {
				$c.=Alert(30);
				//$c.="Success!<br>\n";
				//$c.="<a href='index.php?module=sstars&task=inventory&sub=batch_items&batch_id=".$p->CreatedProjectID()."'><img src='images/buttons/next.gif' border='0'></a>\n";
				$show_form=False;
			}
			else {
				$c.=Alert(31,$p->ShowErrors());
			}
		}
		else {
			$result=$p->EditProject($project_id,$project_code,$title,$description,$category_id,$status_id,$priority_id,$start_date,
							$estimated_completion_date,$estimated_cost,$percentage_completion);
			if ($result) {
				$c.="Success!<br>\n";
				$show_form=False;
			}
			else {
				$c.=$p->ShowErrors();
			}
		}
	}

	/*
		FIELD VALIDATION
	*/
	$fv=new FieldValidation;
	$fv->OpenTag();
	$fv->ValidateFields("invoice_number,cost_price,delivery_date,purchase_date,vendor_name,vendor_id");
	$fv->CloseTag();

	/*
		DESIGN THE FORM
	*/
	//if (!$show_form) { return $c; }
	$c.="<table class='plain_border' cellspacing='0' cellpadding='5' width='500'>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='4'><img src='images/nuvola/32x32/apps/korganizer_todo.png'>Projects</td>\n";
		$c.="</tr>\n";
		$c.="<form method='post' name='add_project' action='index.php?module=projects&task=add_project' onsubmit='return ValidateForm(this);'>\n";
		$c.="<input type='hidden' name='project_id' value=''>\n";
		$c.="<tr>\n";
			$c.="<td>Project Code*</td>\n";
			//if ($ms->FormDynamicLookup()) {
				//$dynamic_lookup="onKeyUp=\"frames['project_id_lookup'].location.href='modules/projects/bin/project/project_code_lookup.php?project_code='+document.getElementById('project_code').value\" autocomplete=off";
				//$dynamic_iframe="<iframe id='project_id_lookup' name='project_id_lookup' src='modules/projects/bin/project/project_code_lookup.php' width='100' height='20' border='1' scrolling='no' border='0' style='border:none;' frameborder='0'></iframe>";
			//}
			//$c.="<td>".DrawInput("project_code","input_reqd",$project_code,"10","20",$dynamic_lookup).$dynamic_iframe."</td>\n";
			$c.="<td>".DrawInput("project_code","input_reqd",$project_code,"10","20","")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Title*</td>\n";
			$c.="<td>".DrawInput("title","input_reqd",$title,"30","255")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top'>Description</td>\n";
			$c.="<td>".DrawTextarea("description","inputbox",$description,"5","40")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Category</td>\n";
			$c.="<td>".ShowDropDown("category_id","category_name","project_category_master","category_id",$category_id,"","WHERE workspace_id = '".$ui->WorkspaceID()."' AND teamspace_id = '".$ui->TeamspaceID()."'")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Status</td>\n";
			$c.="<td>".ShowDropDown("status_id","status_name","project_status_master","status_id",$status_id,"","WHERE workspace_id = '".$ui->WorkspaceID()."' AND teamspace_id = '".$ui->TeamspaceID()."'")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Priority</td>\n";
			$c.="<td>".ShowDropDown("priority_id","priority_name","project_priority_master","priority_id",$priority_id,"","WHERE workspace_id = '".$ui->WorkspaceID()."' AND teamspace_id = '".$ui->TeamspaceID()."'")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Start Date*</td>\n";
			$c.="<td>".DrawInput("start_date", "input_reqd", $start_date, "8", "255", "readonly");
			$c.=JCalendar("start_date","start_date_calendar");
			$c.="</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Est. Completion Date*</td>\n";
			$c.="<td>".DrawInput("estimated_completion_date", "input_reqd", $estimated_completion_date, "8", "255", "readonly");
			$c.=JCalendar("estimated_completion_date","estimated_completion_date_calendar");
			$c.="</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Estimated Cost*</td>\n";
			$c.="<td>".DrawInput("estimated_cost","input_reqd",$estimated_cost,"14","255")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Completion %*</td>\n";
			$c.="<td>".DrawInput("percentage_completion","input_reqd",$percentage_completion,"3","255")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='head' colspan='4'>".DrawSubmit("submit","Save Project","buttonstyle","add_project",$show_submit)."&nbsp;".DrawSubmit("reset",_RESET_FORM_,"buttonstyle","reset")."&nbsp;</td>\n";
		$c.="</tr>\n";
	$c.="</form>\n";
	$c.="</table>\n";
	/* FOCUS ON THE INVOICE NUMBER FIELD */
	$c.="<script language='JavaScript'>\n";
	$c.="document.add_project.project_code.focus();\n";
	$c.="</script>\n";
	return $c;
}
?>