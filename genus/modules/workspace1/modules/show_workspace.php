<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."classes/design/tab_boxes.php";

$tab_array=array("summary","modules","users","roles","statistics");
$tb=new TabBoxes($tab_array,$dr."modules/workspace/modules/show_workspace/");
if ($_GET['block_show']) {
	echo $tb->BlockShow($_GET['block_show']);
}
?>