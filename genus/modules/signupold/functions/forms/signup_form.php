<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."classes/form/form_validate.php";
require_once $GLOBALS['dr']."include/functions/forms/create_fields.php";
require_once $GLOBALS['dr']."include/functions/forms/dynamic_dropdown.php";

require_once $GLOBALS['dr']."modules/signup/classes/signup.php";

function SignupForm() {

	$db=$GLOBALS['db'];
	$database_prefix=$GLOBALS['database_prefix'];
	$wb=$GLOBALS['wb'];
	$c="";

	$fv=new FieldValidation;
	$fv->OpenTag();
	$fv->ValidateFields("title_id,full_name,login,password,identification_number,timezone,security_code");
	$fv->CloseTag();
	$field_validation=$fv->Draw();

	/* SET THE FORM TO SHOW IF WE GET ANY ERRORS */
	$v_show_form=True;
	/* CHECK IF THE FORM HAS BEEN SUBMITTED */
	if (ISSET($_POST['full_name'])) {
		//echo "ok";
		//echo $_SESSION['gd_string'];
		//echo $_POST['security_code'];
		$db->Begin(); /* START THE TRANSACTION */

		$obj_signup=new Signup;
		$obj_signup->GetPostFields();

		/* HERE WE DETERMINE IF THE USER IS JOINING OR ADDING A NEW WORKSPACE */
		if (ISSET($_POST['community_join_add']) && $_POST['community_join_add'] == "add") {
			$result_signup=$obj_signup->Add();
		}
		else {
			$result_signup=$obj_signup->Join();
		}

		if (!$result_signup) {
			/* CREATION OF USER FAILED */
			$db->Rollback();
			echo Alert(39,$obj_signup->ShowErrors());
			/* POPULATE THE FORM TO ALLOW THE USER TO TRY AGAIN */
			$title_id=$_POST['title_id'];
			$full_name=$_POST['full_name'];
			$login=$_POST['login'];
			$password=$_POST['password'];
			$identification_number=$_POST['identification_number'];
			$timezone=$_POST['timezone'];
			$street_address=$_POST['street_address'];
			$postal_code=$_POST['postal_code'];
			$country_id=$_POST['country_id'];
			$workspace_code=$_POST['workspace_code'];
			//$workspace_name=$_POST['workspace_name'];
		}
		else {
			$c.=Alert(40);
			//$db->Rollback();// testing
			$db->Commit();
			$title_id="";
			$full_name="";
			$surname="";
			$login="";
			$password="";
			$identification_number="";
			$timezone="";
			$street_address="";
			$postal_code="";
			$country_id="";
			$workspace_code="";
			//$workspace_name="";
		}
	}
	else {
		$title_id="";
		$full_name="";
		$surname="";
		$login="";
		$password="";
		$identification_number="";
		$timezone="";
		$street_address="";
		$postal_code="";
		$country_id="";
		$workspace_code="";
		//$workspace_name="";
	}

	/*
		DESIGN THE FORM
	*/

	if (!$v_show_form) { $c.="<br><br><h2>Your signup has been successful. Please login <a href='index.php'>Here</a></h2>"; return $c; }
	$c.=$field_validation;
	$c.="<table class='plain'>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='tophead' colspan='4'><img src='".$wb."modules/hrms/images/home/logo22x22.png'>Signup Form</td>\n";
		$c.="</tr>\n";
		$c.="<form method='post' enctype='multipart/form-data' name='signup_form' action='index.php?module=signup' onsubmit='return ValidateForm(this);'>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2'><br></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'>Personal Details</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'><hr color='#669999'></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Title*</td>\n";
			$c.="<td colspan='2'>".ShowDropDown("title_id","title_name","hrms_title_master","title_id",$title_id,"","","input_reqd")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Full Name*</td>\n";
			$c.="<td colspan='2'>".DrawInput("full_name","input_reqd",$full_name,"30","50")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Email*</td>\n";
			$c.="<td colspan='2'>".DrawInput("login","input_reqd",$login,"30","100")." (This will be your login ID)</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Password*</td>\n";
			$c.="<td colspan='2'>".DrawPassword("password",$password,"15","100","input_reqd")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Identification Number*</td>\n";
			$c.="<td colspan='2'>".DrawInput("identification_number","input_reqd",$identification_number,"30","50")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2'><br></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'>Optional Information</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'><hr color='#669999'></td>\n";
		$c.="</tr>\n";
		/*
		$c.="<tr>\n";
			$c.="<td valign='top'>Photograph</td>\n";
			$c.="<td colspan='2'>".DrawFileUpload("photograph","input","","10","80")." (Less than 10k in jpeg format only)</td>\n";
		$c.="</tr>\n";
		*/
		$c.="<tr>\n";
			$c.="<td>Timezone</td>\n";
			$c.="<td colspan='2'>".ShowDropDown("timezone","timezone","hrms_timezone_master","timezone",$timezone,"","","input_reqd")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2'><br></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'>Contact Information</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'><hr color='#669999'></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top'>Street Address</td>\n";
			$c.="<td colspan='2'>".DrawTextarea("street_address","inputstyle",$street_address,4,30)."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Postal Code</td>\n";
			$c.="<td colspan='2'>".DrawInput("postal_code","input",$postal_code,"30","100")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Country</td>\n";
			$c.="<td colspan='2'>".ShowDropDown("country_id","country_name","hrms_country_master","country_id",$country_id,"","")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td colspan='2'><br></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'>Community Information</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'><hr color='#669999'></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' colspan='2'>";
			$c.="Create a new community <input type='radio' name='community_join_add' value='add'><br>";
			$c.="Join an existing community <input type='radio' name='community_join_add' value='join'>";
			//$c.="<input type=button onClick=\"document.getElementById('signup_join_workspace').className='showblk'\" value='Join an existing community' class='buttonstyle'>&nbsp;&nbsp;";
			//$c.="<input type=button onClick=\"document.getElementById('signup_new_workspace').className='showblk'\" value='Create a new community' class='buttonstyle'>";
			$c.="</td>\n";
		$c.="</tr>\n";
		//$c.="<tr>\n";
			//$c.="<td valign='top' colspan='2'>If you have been invited to join an existing community, you should click on the 'Join an existing community button' and complete the search to find your community. Your membership is then queued for approval.</td>\n";
		//$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Search for community</td>\n";
			$c.="<td colspan='2'><nobr>".DrawInput("workspace_code","input_reqd",$workspace_code,"10","100","onKeyUp=\"frames['workspace_code_lookup'].location.href='modules/signup/bin/workspace_code_lookup.php?workspace_code='+document.getElementById('workspace_code').value\" autocomplete=off")."<iframe id='workspace_code_lookup' name='workspace_code_lookup' src='modules/signup/bin/workspace_code_lookup.php' width='200' height='25'scrolling='no' border='0' style='border:none;' frameborder='0'></iframe></nobr></td>\n";
		$c.="</tr>\n";
		//$c.="<tr id='signup_new_workspace' class='hideBlk'>\n";
			//$c.="<td>Give your site a name</td>\n";
			//$c.="<td colspan='2'>".DrawInput("workspace_code_new","input_reqd",$workspace_code,"10","100")."</td>\n";
		//$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'><hr color='#669999'></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'>Security Check</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td colspan='3'>Enter the characters displayed in the image below into the box (security precaution)</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td colspan='3'><img src='bin/signup/random_image.php'></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td colspan='3'>".DrawInput("security_code","input_reqd","","10","100")."</td>\n";
		$c.="</tr>\n";

		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'><hr color='#669999'></td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'>Complete the process</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='head' colspan='4'>".DrawSubmit("submit","Save Details","buttonstyle","signup",True)."&nbsp;</td>\n";
		$c.="</tr>\n";
	$c.="</form>\n";
	$c.="</table>\n";
	return $c;
}
?>