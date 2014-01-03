<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/documents/classes/bookmark_id.php";
require_once $GLOBALS['dr']."modules/documents/functions/browse/my_bookmarks.php";

function LoadTask() {
	$c="";
	/* PROCESS THE ACTIONS */
	if (ISSET($_GET['subtask']) && ISSET($_GET['category_id'])) {
		/* BOOKMARK OBJECT */
		$bi=new BookmarkID;
		$bi->SetParameters($_GET['category_id']);
		/* DELETE USER BOOKMARK */
		if ($_GET['subtask']=="delete") {
			$result=$bi->Delete();
			if ($result) {
				$c.="Success";
			}
			else {
				$c.="Failed";
				$c.=$bi->ShowErrors();
			}
		}
	}
	/* INCLUDE THE BROWSE FUNCTION */
	$c.=MyBookmarks();

	return $c;
}
?>