<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

if (!$_GET['sub']) {
	require_once $dr."modules/sstars/modules/inventory/batch_form.php";
}
else {
	$file=$dr."modules/sstars/modules/inventory/".$_GET['sub'].".php";
	if (file_exists($file)) {
		require_once $file;
	}
}

?>