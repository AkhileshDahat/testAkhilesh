<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

echo Tasks(EscapeData($_GET['project_id']));

function Tasks($project_id) {
	$db=$GLOBALS['db'];

	$sql="SELECT ptm.task_id, ptm.title, ptm.description, ptm.start_date, ptm.estimated_completion_date, ptm.actual_completion_date,
				psm.status_name,
				ppm.priority_name
				FROM ".$GLOBALS['database_prefix']."project_task_master ptm, ".$GLOBALS['database_prefix']."project_status_master psm,
				".$GLOBALS['database_prefix']."project_priority_master ppm
				WHERE ptm.project_id = '".$project_id."'
				AND ptm.status_id = psm.status_id
				AND ptm.priority_id = ppm.priority_id
				ORDER BY ptm.estimated_completion_date
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	$s="<table class='plain_border'>\n";
		$s.="<tr>\n";
			$s.="<td class='colhead' colspan='10'>Project Tasks</td>\n";
		$s.="</tr>\n";
		if ($db->NumRows($result) > 0) {
			$s.="<tr class='colhead'>\n";
				$s.="<td>Title</td>\n";
				$s.="<td>Description</td>\n";
				$s.="<td>Start Date</td>\n";
				$s.="<td>Est Comp Date</td>\n";
				$s.="<td>Actual Comp Date</td>\n";
				$s.="<td>Status</td>\n";
				$s.="<td>Priority</td>\n";
			$s.="</tr>\n";
			while($row = $db->FetchArray($result)) {

				$s.="<tr>\n";
					$s.="<td>".$row['title']."</td>\n";
					$s.="<td>".$row['description']."</td>\n";
					$s.="<td>".$row['start_date']."</td>\n";
					$s.="<td>".$row['estimate_completion_date']."</td>\n";
					$s.="<td>".$row['actual_completion_date']."</td>\n";
					$s.="<td>".$row['status_name']."</td>\n";
					$s.="<td>".$row['priority_name']."</td>\n";
				$s.="</tr>\n";

			}
		}
	$s.="</table>\n";

	return $s;
}
?>