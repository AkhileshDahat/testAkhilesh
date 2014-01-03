<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

echo Summary(EscapeData($_GET['project_id']));

function Summary($project_id) {
	$db=$GLOBALS['db'];

	$sql="SELECT pm.project_id, pm.project_code, pm.title, pm.description, pm.percentage_completion, start_date,
				estimated_completion_date, actual_completion_date, estimated_cost, actual_cost,
				psm.status_name,
				pcm.category_name
				FROM ".$GLOBALS['database_prefix']."project_master pm, ".$GLOBALS['database_prefix']."project_status_master psm,
				".$GLOBALS['database_prefix']."project_category_master pcm
				WHERE pm.project_id = '".$project_id."'
				AND pm.status_id = psm.status_id
				AND pm.category_id = pcm.category_id
				ORDER BY pcm.category_name, psm.status_name
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	$s="<table class='plain_border'>\n";
		$s.="<tr>\n";
			$s.="<td class='colhead' colspan='10'>Project Summary</td>\n";
		$s.="</tr>\n";
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$s.="<tr>\n";
					$s.="<td class='bold'>Project Code</td>\n";
					$s.="<td>".$row['project_code']."</td>\n";
				$s.="</tr>\n";
				$s.="<tr>\n";
					$s.="<td class='bold'>Title</td>\n";
					$s.="<td>".$row['title']."</td>\n";
				$s.="</tr>\n";
				$s.="<tr>\n";
					$s.="<td class='bold'>Description</td>\n";
					$s.="<td>".$row['description']."</td>\n";
				$s.="</tr>\n";
				$s.="<tr>\n";
					$s.="<td class='bold'>Start Date</td>\n";
					$s.="<td>".$row['start_date']."</td>\n";
				$s.="</tr>\n";
				$s.="<tr>\n";
					$s.="<td class='bold'>Est Comp Date</td>\n";
					$s.="<td>".$row['estimated_completion_date']."</td>\n";
				$s.="</tr>\n";
				$s.="<tr>\n";
					$s.="<td class='bold'>Actual Comp Date</td>\n";
					$s.="<td>".$row['actual_completion_date']."</td>\n";
				$s.="</tr>\n";
				$s.="<tr>\n";
					$s.="<td class='bold'>Estimated Cost</td>\n";
					$s.="<td>".$row['estimated_cost']."</td>\n";
				$s.="</tr>\n";
				$s.="<tr>\n";
					$s.="<td class='bold'>Actual Cost</td>\n";
					$s.="<td>".$row['actual_cost']."</td>\n";
				$s.="</tr>\n";
				$s.="<tr>\n";
					$s.="<td class='bold'>Status</td>\n";
					$s.="<td>".$row['status_name']."</td>\n";
				$s.="</tr>\n";
				$s.="<tr>\n";
					$s.="<td class='bold'>Complete</td>\n";
					$s.="<td><img src='images/projects/bar.gif' height='10' width='".$row['percentage_completion']."'> ".$row['percentage_completion']."%</td>\n";
				$s.="</tr>\n";
			}
		}
	$s.="</table>\n";

	return $s;
}
?>