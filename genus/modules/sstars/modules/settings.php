<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

echo "<a href='index.php?module=sstars&task=settings&sub=import_users'>Import Users</a>\n";

$file=$dr."modules/sstars/modules/settings/".$_GET['sub'].".php";
if (file_exists($file)) {
	require_once $file;
}

?>