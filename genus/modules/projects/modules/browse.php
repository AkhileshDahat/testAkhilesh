<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

//require_once $GLOBALS['dr']."classes/design/tab_boxes.php";

//$tab_array=array("summary","tasks","resources","gantt");
//$tb=new TabBoxes($tab_array,$dr."modules/projects/modules/browse_project/");

require_once $GLOBALS['dr']."modules/projects/functions/browse/show_projects.php";

function LoadTask() {

	$c="";
	$c.=ShowProjects();
	return $c;
}

?>