<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

//require_once($GLOBALS['dr']."classes/form/show_results.php");

//require_once($GLOBALS['dr']."include/rico2rc2/examples/php/chklang2.php");

function BrowseAllUsers() {

	$sql = "SELECT full_name, login, identification_number,
					concat('<a href=index.php?module=core&task=browse_all_users&subtask=del&user_id=',user_id,'>Edit</a>') as doedit,
					concat('<a href=index.php?module=core&task=browse_all_users&subtask=del&user_id=',user_id,'>Delete</a>') as dodelete
					FROM core_user_master
					ORDER BY full_name DESC";

	$sql = str_replace("\n","",$sql);
	//$sql = str_replace("  ","",$sql);
	$sql = str_replace("\t","",$sql);
	//$sql = str_replace(Chr(13),"",$sql);
	//echo $sql;
	$_SESSION['ex2'] = $sql;

	$head = $GLOBALS['head'];
	$head->IncludeFile("rico21");

	$c="

	<p class='ricoBookmark'><span id='ex2_timer' class='ricoSessionTimer'></span><span id='ex2_bookmark'>&nbsp;</span></p>
	<table id='ex2' class='ricoLiveGrid' cellspacing='0' cellpadding='0'>
	<colgroup>
	<col style='width:150px;' >
	<col style='width:150px;' >
	<col style='width:50px;' >
	<col style='width:50px;' >
	<col style='width:50px;' >
	</colgroup>
	  <tr>
		  <th>Name</th>
		  <th>Login</th>
		  <th>ID</th>
		  <th>Edit</th>
		  <th>Remove</th>
	  </tr>
	</table>
	";

	return $c;

}

?>