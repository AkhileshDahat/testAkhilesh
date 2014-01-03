<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."modules/sstars/functions/inventory/batch_items.php";
require_once $dr."modules/sstars/functions/inventory/show_batch_items.php";

echo BatchItems();

//echo CurveBox(ShowBatchItems(EscapeData($_GET['batch_id'])));
echo ShowBatchItems(EscapeData($_GET['batch_id']));
?>