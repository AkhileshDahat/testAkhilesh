<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."classes/form/create_form.php"; /* FORM CREATION CLASS */
require_once $GLOBALS['dr']."modules/messaging/classes/new_message.php";
require_once $GLOBALS['dr']."modules/groups/functions/browse/group_list.php";

function LoadTask() {

	$c="";

	/* THE FORM IS BEING SUBMITTED */
	if (ISSET($_POST['message'])) {
		$nm=new NewMessage;
		//echo $_POST['message'];
		$nm->SetParameters($GLOBALS['workspace_id'],$GLOBALS['teamspace_id'],$_SESSION['user_id'],$_POST['message']);
		//$message=EscapeData($_POST['message']);
		if (ISSET($_POST['group_id'])) {
			$arr=$_POST['group_id'];
			for ($i=0;$i<count($arr);$i++) {
				$nm->SendMessageGroup(EscapeData($arr[$i]));
			}
		}
	}

	/* CREATION OF THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=messaging","post","send_message","","500");
	//$form->Header("crystalclear/48x48/apps/web.png","Send message");
	$form->Textarea("Message","message","10","30");
	$form->TextAreaEnterSubmit("message"); /* SUBMIT WHEN ENTER PRESSED */
	$form->Submit("Send Now","FormSubmit");
	$form->SetFocus("message"); /* FOCUS CURSOR ON THE MESSAGE BOX*/

	//echo CurveBox(CreateMessage($form)); /* CREATE THE OVERALL FORM */

	$c.="<table class='plain'>\n";
		$c.="<tr>\n";
			$c.="<td valign='top'>\n";
			$c.=$form->DrawForm();
			$c.="</td>\n";
			$c.="<td valign='top'>\n";
			$c.=GroupList($_SESSION['user_id'],$GLOBALS['workspace_id'],$GLOBALS['teamspace_id']);
			$c.="</td>\n";
		$c.="</tr>\n";
		$c.="</form>\n"; /* WE HAVE TO SPECIFY THIS BECAUSE WE DID NOT CALL IT IN THE CLASS */
	$c.="</table>\n";
	return $c;
}
?>
