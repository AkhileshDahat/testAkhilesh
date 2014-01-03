<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."modules/sstars/classes/new_user.php";

echo ImportUsers();

function ImportUsers() {

	/*
		THE FORM IS BEING SUBMITTED
	*/
	if ($_GET['import']=="y") {
		$err=False; /* ASSUME NO ERRORS AS WE GO ALONG */
		$c.="Saving...<br>";

		$uploadfile=$dr."modules/sstars/bin/settings/".basename($_FILES['users']['name']);
		//$uploadfile=$dr."modules/sstars/bin/settings/".$user_id;

		if (move_uploaded_file($_FILES['users']['tmp_name'], $uploadfile)) {
		  echo "File is valid, and was successfully uploaded<br>\n";

			foreach (file($uploadfile) as $line_num => $line) {
				//echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
				$pieces=explode(",",$line);
				$user_id=$pieces[0];
				$username=$pieces[1];
				$password=$pieces[2];
				$full_name=$pieces[3];
				$department_id=$pieces[4];
				$superior_user_id=$pieces[5];

				$nu=new NewUser;
				$user_success=$nu->AddUser($user_id,$username,$password,$full_name,$department_id,$superior_user_id);
				if (!$user_success) { echo $nu->ShowErrors(); } else { echo "Success<br>"; }

			}
		} else {
		   echo "Possible file upload attack!\n";
		}
	}

	/*
		FIELD VALIDATION
	*/
	/*
	$fv=new FieldValidation;
	$fv->OpenTag();
	$fv->ValidateFields("invoice_number,cost_price,delivery_date,purchase_date,vendor_name,vendor_id");
	$fv->CloseTag();
	*/

	/*
		DESIGN THE FORM
	*/
	//if (!$show_form) { return $c; }
	$c.="<table class='plain_border' cellspacing='0' cellpadding='5'>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' class='colhead' colspan='2'><img src='images/nuvola/32x32/apps/korganizer_todo.png'>Import Users</td>\n";
		$c.="</tr>\n";
		$c.="<form enctype='multipart/form-data' method='post' name='batch_form' action='index.php?module=sstars&task=settings&sub=import_users&import=y' onsubmit='return ValidateForm(this);'>\n";
		$c.="<tr>\n";
			$c.="<td>File</td>\n";
			$c.="<td><input type='file' name='users' class='buttonstyle'><input type='submit' name='import_users' class='buttonstyle'></td>\n";
		$c.="</tr>\n";
		$c.="</form>\n";
		$c.="<tr>\n";
			$c.="<td valign='top' colspan='2'>The syntax for the file is:<p>";
			$c.="[user id],[username],[password],[full name],[superiod id],[department id]<p>";
			$c.="e.g. 12345,joe,mypassword,joe andrews,12346,2";
			$c.="</td>\n";
		$c.="</tr>\n";
	$c.="</table>\n";
	return $c;
}