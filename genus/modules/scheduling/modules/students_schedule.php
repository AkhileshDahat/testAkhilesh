<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/scheduling/classes/scheduling_students_reserved_slots.php";
require_once $GLOBALS['dr']."modules/scheduling/functions/browse/browse_students_timetable.php";
require_once $GLOBALS['dr']."classes/form/create_form.php";

function LoadTask() {

	$c="";

	/*
	**************************************************************************
		PERFORM THE ACTIONS HERE BEFORE WE START DISPLAYING STUFF
	***************************************************************************
	*/
	if (ISSET($_GET['save'])) {
		/* CREATE THE OBJECT */
		$obj_slrs=new SchedulingStudentReservedSlots;
		/* ADD - NO ID*/
		if (!ISSET($_POST['category_id']) || EMPTY($_POST['category_id'])) {
			$result_add=$obj_slrs->Add($_GET['time_start'],$_GET['time_end'],$_GET['day_of_week'],"own");
		}

		if (!$result_add) {
			$c.="Failed";
			$c.=$obj_slrs->ShowErrors();
		}
		else {
			$c.="Success";
		}
	}

	/* DELETE */
	if (ISSET($_GET['reset'])) {

		$obj_slrs=new SchedulingStudentReservedSlots;
		$result_del=$obj_slrs->Reset();
		if (!$result_del) {
			$c.="Failed";
			$c.=$obj_slrs->ShowErrors();
		}
		else {
			$c.="Success";
		}
	}

	$description="";
	if (ISSET($_GET['description'])) {
		$description=EscapeData($_GET['description']);
	}

	$form=new CreateForm;
	$form->SetCredentials("index.php?module=scheduling&task=students_schedule","get","students_schedule","onSubmit=\"ValidateForm(this)\"");
	$form->SetParam("btn_save","Browse Now");
	$form->SetParam("show_btn_cancel",False);
	$form->Hidden("module","scheduling");
	$form->Hidden("task","students_schedule");
	$form->Header("crystalclear/48x48/apps/access.png","students Timetable");
	$form->ShowDropDown("Group","description","description","scheduling_subject_detail","description",$description,"","WHERE workspace_id = ".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'],"onChange='this.form.submit()'","input_reqd");
	$c.=$form->DrawForm();

	if (strlen($description)>0) {
		$c.=BrowseStudentsTimetable();
	}

	return $c;
}
?>