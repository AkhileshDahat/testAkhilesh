<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/teamspace/classes/teamspace_id.php";

function LoadTask() {
	$c="";

	if (ISSET($_GET['subtask']) && $_GET['subtask'] == "del") {
		$obj_ti=new TeamspaceID;
		$obj_ti->SetParameters($_GET['teamspace_id']);
		$result=$obj_ti->Delete();
		if ($result) {
			$c.="Success";
		}
		else {
			$c.="Failed";
			$c.=$obj_ti->ShowErrors();
		}
	}


	$sql = "SELECT name,description,
					concat('<a href=index.php?module=teamspace&task=browse_teamspaces&subtask=del&teamspace_id=',teamspace_id,'>Delete</a>') as dodelete
					FROM core_teamspace_master
					ORDER BY name DESC";

	$sql = str_replace("\n","",$sql);
	//$sql = str_replace("  ","",$sql);
	$sql = str_replace("\t","",$sql);
	//$sql = str_replace(Chr(13),"",$sql);
	//echo $sql;
	$_SESSION['ex2'] = $sql;

	$head = $GLOBALS['head'];
	$head->IncludeFile("rico2rc2");

	$c.="

	<p class='ricoBookmark'><span id='ex2_timer' class='ricoSessionTimer'></span><span id='ex2_bookmark'>&nbsp;</span></p>
	<table id='ex2' class='ricoLiveGrid' cellspacing='0' cellpadding='0'>
	<colgroup>
	<col style='width:150px;' >
	<col style='width:250px;' >
	<col style='width:50px;' >
	</colgroup>
	  <tr>
		  <th>Name</th>
		  <th>Desc</th>
		  <th>Remove</th>
	  </tr>
	</table>
	";

	return $c;

}

?>