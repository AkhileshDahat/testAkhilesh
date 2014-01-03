<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

require_once($GLOBALS['dr']."include/functions/misc/yes_no_image.php");
require_once($GLOBALS['dr']."include/functions/date_time/timestamptz_to_friendly.php");
require_once($GLOBALS['dr']."include/functions/filesystem/size_int.php");

require_once($GLOBALS['dr']."modules/documents/classes/document_id.php");

function Summary() {

	GLOBAL $document_id;

	$di=new DocumentID;
	$result=$di->SetParameters($document_id);
	if (!$result) { return $di->ShowErrors(); }

	$c="<table border='0' cellpadding='0' width='100%' class='plain'>\n";
		$c.="<tr class='modulehead'>\n";
			$c.="<td colspan='2'>Document Details</td>\n";
		$c.="</tr>\n";
		$c.=ShowRow($di,"Title","title");
		$c.=ShowRow($di,"Description","description");
		$c.=ShowBreak();
		$c.=ShowRow($di,"Owner","user_created");
		$c.=ShowRow($di,"Version Number","version_number");
		$c.=ShowRow($di,"Status","status_name");
		$c.=ShowRow($di,"Added","date_added");
		$c.=ShowRow($di,"Start publishing","date_start_publishing");
		$c.=ShowRow($di,"End publishing","date_end_publishing");
		$c.=ShowBreak();
		$c.=ShowRow($di,"Filename","filename");
		$c.=ShowRow($di,"File Type","filetype");
		//$c.=ShowRow($di,"File Size","filesize");
		$c.="<tr>\n";
			$c.="<td class='bold'>Size</td>\n";
			$c.="<td>".SizeFromInt($di->GetColVal("filesize"))."</td>\n";
		$c.="</tr>\n";
		$c.=ShowBreak();
		$c.=ShowYesNoRow($di,"Checked Out","checked_out");
		//$c.=ShowRow($di,"User Checked Out","user_checked_out");
		//$c.=ShowRow($di,"Date Checked Out","date_checked_out");
		/* CHECK OUT DATE */
		$c.="<tr>\n";
			$c.="<td class='bold'>Date Checked Out</td>\n";
			$date_checked_out=$di->GetColVal("date_checked_out");
			if (!EMPTY($date_checked_out)) {
				$c.="<td>".TimestamptzToFriendly($di->GetColVal("date_checked_out"))."</td>\n";
			}
			else {
				$c.="<td></td>\n";
			}
		$c.="</tr>\n";
		$c.=ShowBreak();
		$c.=ShowYesNoRow($di,"Locked","locked");
		//$c.=ShowRow($di,"Locked by","user_locked");
		/* CHECK OUT DATE */
		$c.="<tr>\n";
			$c.="<td class='bold'>Date Locked</td>\n";
			$date_locked=$di->GetColVal("date_locked");
			if (!EMPTY($date_locked)) {
				$c.="<td>".TimestamptzToFriendly($di->GetColVal("date_locked"))."</td>\n";
			}
			else {
				$c.="<td></td>\n";
			}
		$c.="</tr>\n";
		//$c.=ShowRow($di,"Date Locked","date_locked");
	$c.="</table>\n";

	return $c;
}

function ShowRow($di,$desc,$val) {
	$c="<tr>\n";
		$c.="<td class='bold'>".$desc."</td>\n";
		$c.="<td>".$di->GetColVal($val)."</td>\n";
	$c.="</tr>\n";
	return $c;
}

function ShowYesNoRow($di,$desc,$val) {
	$c="<tr>\n";
		$c.="<td class='bold'>".$desc."</td>\n";
		$c.="<td>".YesNoImage($di->GetColVal($val))."</td>\n";
	$c.="</tr>\n";
	return $c;
}

function ShowBreak() {
	$c="<tr>\n";
		$c.="<td colspan='2'><hr></td>\n";
	$c.="</tr>\n";
	return $c;
}
?>