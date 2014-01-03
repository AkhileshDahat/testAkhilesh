<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/helpdesk/functions/browse/ticket_tags.php";
require_once $GLOBALS['dr']."modules/helpdesk/classes/ticket_id.php";

function Tags() {

	$c="";

	/* QUERYSTRING PROCESSING */
	if (ISSET($_GET['ticket_id']) && IS_NUMERIC($_GET['ticket_id'])) {
		$v_ticket_id=$_GET['ticket_id'];
	}
	else {
		$v_ticket_id=null;
	}

	/* FORM PROCESSING */

	if (ISSET($_POST['submit'])) {

		/* GET THE CATEGORY FROM THE QUERYSTRING */
		$v_ticket_id=$_GET['ticket_id'];

		if (ISSET($_POST['select1'])) {

			/* ADD */
			$v_tag_id_add_arr=$_POST['select1'];
			for ($i=0;$i<count($v_tag_id_add_arr);$i++) {
				$v_tag_id=EscapeData($v_tag_id_add_arr[$i]);
				/* NEW TICKET ID OBJECT */
				$obj_ti=new TicketID;
				/* SET PARAMETERS */
				$obj_ti->SetParameters($v_ticket_id);
				/* ADD TO DELEGATION TABLE */
				$del_result=$obj_ti->AddTicketTag($v_tag_id);
				if (!$del_result) {
					$c.=$obj_ti->ShowErrors();
				}
				else {
					$c.="Success";
				}
			}
		}
		/* DELETE */
		if (ISSET($_POST['select2'])) {
			$v_tag_id_del_arr=$_POST['select2'];
			for ($i=0;$i<count($v_tag_id_del_arr);$i++) {
				$v_tag_id=EscapeData($v_tag_id_del_arr[$i]);
				/* NEW TICKET ID OBJECT */
				$obj_ti=new TicketID;
				/* SET PARAMETERS */
				$obj_ti->SetParameters($v_ticket_id);
				/* ADD TO DELEGATION TABLE */
				$del_result=$obj_ti->DeleteTicketTag($v_tag_id);
				if (!$del_result) {
					$c.=$obj_ti->ShowErrors();
				}
				else {
					$c.="Success";
				}
			}
		}
	}

	/* SHOW THE FORM */
	$c.=TicketTags($v_ticket_id);

	return $c;
}
?>