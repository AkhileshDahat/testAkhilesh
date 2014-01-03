<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");
require_once($GLOBALS['dr']."modules/scheduling/classes/subject_detail_id.php");

function BrowseSubjectDetailInfo($subject_detail_id) {

	$c="";

	$obj_sdi=new SubjectDetailID;
	$obj_sdi->SetParameters($subject_detail_id);

	$c.="<table class='plain'>\n";
		$c.="<tr>\n";
			$c.="<td>Subject Name</td>\n";
			$c.="<td>".$obj_sdi->GetInfo("subject_name")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Lecturer</td>\n";
			$c.="<td>".$obj_sdi->GetInfo("full_name")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Start</td>\n";
			$c.="<td>".$obj_sdi->GetInfo("date_start")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>End</td>\n";
			$c.="<td>".$obj_sdi->GetInfo("date_end")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Duration</td>\n";
			$c.="<td>".$obj_sdi->GetInfo("duration_hours")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Capacity</td>\n";
			$c.="<td>".$obj_sdi->GetInfo("capacity")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>Description</td>\n";
			$c.="<td>".$obj_sdi->GetInfo("description")."</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td valign='top'>Items Required</td>\n";
			$c.="<td>".ShowItemsReq($obj_sdi)."</td>\n";
		$c.="</tr>\n";

	$c.="</table>\n";

	return $c;

}

function ShowItemsReq($obj) {

	$c="";
	$arr_items=$obj->GetItemReqsArr();
	foreach ($arr_items as $a) {
		$c.=$a."<br />\n";
	}
	return $c;
}
?>