<?php
/** ensure this file is being included by a parent file */
define( '_VALID_MVH', 1 );

function LogBatchHistory($batch_id,$description,$user_id) {
	$db=$GLOBALS['db'];
	$sql="INSERT INTO ".$GLOBALS['database_prefix']."batch_history
				(batch_id,description,user_id,log_date)
				VALUES (
				'".$batch_id."',
				'".$description."',
				'".$user_id."',
				sysdate()
				)";
	//echo $sql;
	$result=$db->query($sql);
	if ($result) {
		return True;
	}
	else {
		return False;
	}
}
?>