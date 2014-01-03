<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."include/functions/forms/create_fields.php");
require_once($GLOBALS['dr']."include/functions/forms/dynamic_dropdown.php");
require_once($GLOBALS['dr']."classes/form/form_validate.php");
require_once($GLOBALS['dr']."include/functions/misc/jcalendar.php");
require_once($GLOBALS['dr']."modules/workspace/classes/new_workspace.php");

function AddWorkspace() {

	$c="";
	$ui=$GLOBALS['ui'];

	$show_form=True;

	/* INCLUDE THE JAVASCRIPT CALENDAR IN THE HEAD */
	$GLOBALS['head']->IncludeFile("jscalendar");

	/*
		THE FORM IS BEING SUBMITTED
	*/
	if (ISSET($_POST['add_workspace'])) {
		$err=False; /* ASSUME NO ERRORS AS WE GO ALONG */
		//$c.="Saving...<br>";
		$workspace_id=$_GET['workspace_id'];
		$workspace_code=$_POST['workspace_code'];
		$name=$_POST['name'];
		$description=$_POST['description'];
		$logo=$_POST['logo'];
		$max_teamspaces=$_POST['max_teamspaces'];
		$max_size=$_POST['max_size'];
		$max_users=$_POST['max_users'];
		$date_start=$_POST['date_start'];
		$date_end=$_POST['date_end'];
		$status_id=$_POST['status_id'];
		$show_submit=True;

		if (ISSET($_POST['enterprise'])) {
			$enterprise=EscapeData($_POST['enterprise']);
		}
		else {
			$enterprise="";
		}

		$p=new NewWorkspace; /* OBJECT TO CREATE A NEW BATCH */
		/* IF THERE'S NO BATCH ID THEN WE'RE INSERTING A NEW RECORD */
		if (!$_GET['workspace_id']) {
			$result=$p->AddWorkspace($workspace_code,$name,$description,$logo,$max_teamspaces,$max_size,$max_users,$date_start,
							$date_end,$status_id,$enterprise,$_SESSION['user_id'],$GLOBALS['ui']->RoleID());
			if ($result) {
				$p->WorkspaceModules($p->WorkspaceID(),$_SESSION['user_id']);
				$p->WorkspaceTaskACL($p->WorkspaceID(),$p->GetVar("workspace_administrator_role_id"),"y");
				echo "Success";
				echo Alert(32);
				$show_submit=False;
			}
			else {
				echo Alert(33,$p->ShowErrors());
			}
		}
		else {
			$result=$p->EditWorkspace($workspace_id,$workspace_code,$name,$description,$logo,$max_teamspaces,$max_size,$max_users,$date_start,
							$date_end,$status_id,$enterprise,$GLOBALS['user_id']);
			if ($result) {
				$c.="Success!<br>\n";
				$show_form=False;
			}
			else {
				$c.=$p->ShowErrors();
			}
		}
	}
	else if (!EMPTY($_GET['workspace_id'])) {
		require_once $GLOBALS['dr']."modules/workspace/classes/workspace_id.php";

		$workspace_id=$_GET['workspace_id']; // WE NEED TO INSTANTIATE THE VARIABLE

		$wid=new WorkspaceID();
		$wid->SetParameters($workspace_id);
		$workspace_code=$wid->GetColVal("workspace_code");
		$name=$wid->GetColVal("name");
		$description=$wid->GetColVal("description");
		$logo=$wid->GetColVal("logo");
		$max_teamspaces=$wid->GetColVal("max_teamspaces");
		$max_size=$wid->GetColVal("max_size");
		$max_users=$wid->GetColVal("max_users");
		$date_start=$wid->GetColVal("date_start");
		$date_end=$wid->GetColVal("date_end");
		$enterprise=$wid->GetColVal("enterprise");
		$status_id=$wid->GetColVal("status_id");
		$show_submit="Edit Workspace";

		/*
		$name=$wi_edit->Name();
		$description=$wi_edit->Description();
		$logo=$wi_edit->Logo();
		$max_teamspaces=$wi_edit->MaxTeamspaces();
		$max_size=$wi_edit->MaxSize();
		$max_users=$wi_edit->MaxUsers();
		$date_start=$wi_edit->AvailableStartDate();
		$date_end=$wi_edit->AvailableEndDate();
		$status_id=$wi_edit->StatusID();
		$enterprise=$wi_edit->Enterprise();
		$status_name=$wi_edit->StatusName();
		*/
	}
	else {
		/* SET THE VARIABLES TO NONE*/
		$workspace_id="";
		$workspace_code="";
		$name="";
		$description="";
		$logo="";
		$max_teamspaces="";
		$max_size="";
		$max_users="";
		$date_start="";
		$date_end="";
		$status_id="";
		$enterprise="";
		$status_name="";
		$show_submit="";
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
	$c="<table class='plain_border' cellspacing='0' cellpadding='5' width='500'>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='4'><img src='images/nuvola/32x32/apps/korganizer_todo.png'>Workspace</td>\n";
		$c.="</tr>\n";
		$c.="<form method='post' name='add_workspace' action='index.php?module=workspace&task=add_workspace&workspace_id=".$workspace_id."' onsubmit='return ValidateForm(this);'>\n";
		//$c.="<input type='hidden' name='workspace_id' value='".$_GET['workspace_id']."'>\n";
		$c.="<tr>\n";
			$c.="<td>Workspace Code*</td>\n";
			//if ($ms->FormDynamicLookup()) {
				//$dynamic_lookup="onKeyUp=\"frames['workspace_id_lookup'].location.href='modules/workspace/bin/workspace/workspace_code_lookup.php?workspace_code='+document.getElementById('workspace_code').value\" autocomplete=off";
				//$dynamic_iframe="<iframe id='workspace_id_lookup' name='workspace_id_lookup' src='modules/workspace/bin/workspace/workspace_code_lookup.php' width='100' height='20' border='1' scrolling='no' border='0' style='border:none;' frameborder='0'></iframe>";
			//}
			//$c.="<td>".DrawInput("workspace_code","input_reqd",$workspace_code,"10","20",$dynamic_lookup).$dynamic_iframe."</td>\n";
			$c.="<td>".DrawInput("workspace_code","input_reqd",$workspace_code,"10","20","")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Name*</td>\n";
			$c.="<td>".DrawInput("name","input_reqd",$name,"30","255")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top'>Description</td>\n";
			$c.="<td>".DrawTextarea("description","inputbox",$description,"5","40")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Logo*</td>\n";
			$c.="<td>".DrawInput("logo", "input_reqd", $logo, "40", "255", "readonly");
			$c.="<img src='images/nuvola/16x16/actions/viewmag.png' OnClick=\"window.open('bin/new_window/browse_icons.php?form_name=add_workspace&field_text=logo','Choose Icon','scrollbars=yes,status=no,width=400,height=350')\">\n";
			$c.="</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Max Teamspaces*</td>\n";
			$c.="<td>".DrawInput("max_teamspaces","input_reqd",$max_teamspaces,"5","255")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Max Size* (MB)</td>\n";
			$c.="<td>".DrawInput("max_size","input_reqd",$max_size,"5","255")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Max Users*</td>\n";
			$c.="<td>".DrawInput("max_users","input_reqd",$max_users,"5","255")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Available Start Date*</td>\n";
			$c.="<td>".DrawInput("date_start", "input_reqd", $date_start, "8", "255", "readonly");
			$c.=JCalendar("date_start","date_start_calendar");
			$c.="</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Available End Date*</td>\n";
			$c.="<td>".DrawInput("date_end", "input_reqd", $date_end, "8", "255", "readonly");
			$c.=JCalendar("date_end","date_end_calendar");
			$c.="</td>\n";
		$c.="</tr>\n";

		$c.="<tr>\n";
			$c.="<td>Enterprise</td>\n";
			$c.="<td>".DrawCheckbox("enterprise","y",$enterprise,"")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Status</td>\n";
			$c.="<td>".ShowDropDown("status_id","status_name","core_space_status_master","status_id",$status_id,"","")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='head' colspan='4'>".DrawSubmit("submit","Save Workspace","buttonstyle","add_workspace",$show_submit)."</td>\n";
		$c.="</tr>\n";
	$c.="</form>\n";
	$c.="</table>\n";
	/* FOCUS ON THE INVOICE NUMBER FIELD */
	$c.="<script language='JavaScript'>\n";
	$c.="document.add_workspace.workspace_code.focus();\n";
	$c.="</script>\n";
	return $c;
}
?>