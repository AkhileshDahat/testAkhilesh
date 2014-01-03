<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."include/functions/teamspaces/non_enterprise_user_teamspaces.php";


echo ShowTeamSpaceModules($ui->TeamspaceID(),$ui->WorkspaceID());

?>