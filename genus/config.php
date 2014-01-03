<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

ob_start();

//ini_set("display_errors","Off");
//error_reporting(0);

require_once "site_config.php";
require_once "preferences.php";

/* CLEAN USER DATA FROM GET POST AND COOKIE */
require_once $dr."include/functions/userdata/cleanuserdata.php";

/* COMMON CLASSES */
require_once $dr."classes/design/html_head.php";

/* ERROR HANDLER */
require_once $dr."classes/errors/errors.php";
global $errors;
$errors = new errors;

/* COMMONLY USED FUNCTIONS */
require_once $dr."include/functions/string/escape_data.php";
require_once $dr."include/functions/db/get_column_value.php";
require_once $dr."include/functions/db/row_exists.php";
require_once $dr."include/functions/misc/alert.php";
require_once $dr."include/functions/design/module_menu.php";
require_once $dr."include/functions/string/null_check.php";
require_once $dr."include/functions/misc/log_history.php";
require_once $dr."include/functions/misc/var_isset.php";
require_once $dr."include/functions/errors/error_page_top.php";
?>