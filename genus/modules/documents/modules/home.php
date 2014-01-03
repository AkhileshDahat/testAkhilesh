<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/documents/classes/document_id.php";

require_once $GLOBALS['dr']."modules/documents/functions/browse/display_categories.php";
require_once $GLOBALS['dr']."modules/documents/functions/browse/display_files.php";

require_once $GLOBALS['dr']."modules/documents/classes/category_id.php";


function LoadTask() {

	$ui=$GLOBALS['ui'];

	$c="";

	/********** PROCESSING THE ICON ACTIONS WHICH ARE FILE SPECIFIC ************/
	if (ISSET($_POST['button']) && ISSET($_POST['document_id'])) {

		/* SET THE VARIABLE OF THE BUTTON */
		$v_post_action=$_POST['button'];

		/* LOOP ALL THE DOCUMENT IDS */
		$document_ids=$_POST['document_id'];
		for ($i=0;$i<count($document_ids);$i++) {
			$document_id=EscapeData($document_ids[$i]);
			/* CREATE A NEW OBJECT */
			$di=new DocumentID;
			$di->SetParameters($document_id);

			/* WE'RE DELETING VERSIONS HERE */
			if ($v_post_action == "delete_version") {
				$di->DeleteVersion();
			}
			/* WE'RE DELETING ENTIRE FILES & VERSIONS HERE */
			elseif ($v_post_action == "delete_file") {
				//echo $document_id;
				$result=$di->DeleteFile();
				if (!$result) {
					$c.="Failed to delete file: ".$di->ShowErrors();
				}
			}
			/* WE'RE CHECKING OUT DOCUMENTS HERE */
			elseif ($v_post_action == "check_out") {
				$result=$di->CheckOut();

				if (!$result) { $c.=$di->ShowErrors(); } else { $c.="Checkout successful"; }
			}
			/* WE'RE LOCKING DOCUMENTS */
			elseif ($v_post_action == "lock_document") {
				$di->LockDocument();
			}
			/* WE'RE UNLOCKING DOCUMENTS */
			elseif ($v_post_action == "unlock_document") {
				$di->UnLockDocument();
			}
			/* WE'RE CUTTING DOCUMENTS */
			elseif ($v_post_action == "cut_document") {
				$di->CutDocument();
			}
		}
	}

	/********** PROCESS OTHER ACTIONS ************/
	/* WE'RE DELETING FOLDERS */
	if (ISSET($_GET['action'])) {
		if ($_GET['action'] == "delete_category") {
				$o_ci = new CategoryID;
				$o_ci->SetParameters($_GET['del_category_id']);
				$v_result=$o_ci->Delete();
				if ($v_result) {
					$c.="Success<br>";
				}
				else {
					$c.=$o_ci->ShowErrors();
					$c.="Failure<br>";
				}
		}
	}
	/* END ICON FILE SPECIFIC PROCESSING */

	$teamspace_id=$ui->TeamspaceID();
	if (ISSET($_GET['category_id']) && IS_NUMERIC($_GET['category_id'])) { $category_id=EscapeData($_GET['category_id']); } else { $category_id=0; }
	if (ISSET($_GET['category_id']) && IS_NUMERIC($_GET['category_id'])) { $category_id=EscapeData($_GET['category_id']); } else { $category_id=0; }

	if (EMPTY($teamspace_id)) {
		$v_type="workspace";
		$v_id=$ui->WorkspaceID();
	}
	else {
		$v_type="teamspace";
		$v_id=$ui->TeamspaceID();
	}

	if (ISSET($_GET['category_id']) && IS_NUMERIC($_GET['category_id'])) {
		$v_category_id=$_GET['category_id'];
		$ci=new CategoryID;
	}
	else {
		$v_category_id=0;
	}

	if (defined( '_VALID_MVH_MOBILE_' )) {
		$c.=DisplayCategories($v_category_id,$v_type,$v_id,3);
	}
	else {
		$c.=DisplayCategories($v_category_id,$v_type,$v_id);
	}
	$c.="<p>";


	/* DISPLAY THE FILES IN THE FOLDER */
	if (!EMPTY($v_category_id) && $ci->CountFiles($v_category_id) > 0) {
		if (defined( '_VALID_MVH_MOBILE_' )) {
			$c.=DisplayFiles($v_category_id);
		}
		else {
			$c.=DisplayFiles($v_category_id);
		}
	}
	return $c;
}
?>