<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";

function AddTimes($facility_id="",$mon_from="",$mon_to="",$tue_from="",$tue_to="",$wed_from="",$wed_to="",$thur_from="",$thur_to="",$fri_from="",$fri_to="",$sat_from="",$sat_to="",$sun_from="",$sun_to="",$mon_na="",$tue_na="",$wed_na="",$thur_na="",$fri_na="",$sat_na="",$sun_na="") {

	/* IF EITHER ONE IS NULL THEN SELECT THE NOT AVAILABLE CHECKBOX */
	if (IS_NULL($mon_from) || IS_NULL($mon_to)) { $mon_na=True; } else { $mon_na=False; }
	if (IS_NULL($tue_from) || IS_NULL($tue_to)) { $tue_na=True; } else { $tue_na=False; }
	if (IS_NULL($wed_from) || IS_NULL($wed_to)) { $wed_na=True; } else { $wed_na=False; }
	if (IS_NULL($thur_from) || IS_NULL($thur_to)) { $thur_na=True; } else { $thur_na=False; }
	if (IS_NULL($fri_from) || IS_NULL($fri_to)) { $fri_na=True; } else { $fri_na=False; }
	if (IS_NULL($sat_from) || IS_NULL($sat_to)) { $sat_na=True; } else { $sat_na=False; }
	if (IS_NULL($sun_from) || IS_NULL($sun_to)) { $sun_na=True; } else { $sun_na=False; }

	$form=new CreateForm;
	$form->SetCredentials("index.php?module=facilities&task=facilities&subtask=times&jshow=times&facility_id=".EscapeData($_GET['facility_id']),"post","add_facility_times");
	$form->Header("crystalclear/48x48/apps/access.png","Facility Times");
	//$form->Hidden("facility_id",$facility_id);

	$form->TimeFromTo("Monday","mon_from","mon_to",$mon_from,$mon_to,"mon_na",$mon_na);
	$form->TimeFromTo("Tuesday","tue_from","tue_to",$tue_from,$tue_to,"tue_na",$tue_na);
	$form->TimeFromTo("Wednesday","wed_from","wed_to",$wed_from,$wed_to,"wed_na",$wed_na);
	$form->TimeFromTo("Thursday","thur_from","thur_to",$thur_from,$thur_to,"thur_na",$thur_na);
	$form->TimeFromTo("Friday","fri_from","fri_to",$fri_from,$fri_to,"fri_na",$fri_na);
	$form->TimeFromTo("Saturday","sat_from","sat_to",$sat_from,$sat_to,"sat_na",$sat_na);
	$form->TimeFromTo("Sunday","sun_from","sun_to",$sun_from,$sun_to,"sun_na",$sun_na);
	$form->Submit("Save","FormSubmit");
	$form->CloseForm();
	return $form->DrawForm();
}

?>