<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."include/functions/teamspace/enterprise_user_teamspaces.php";

echo "<table class='plain' border='1'>\n";
	echo "<tr>\n";
		echo "<td>";
		EnterpriseUserTeamspaces($user_id,$ui->WorkspaceID(),$ui->TeamspaceID(),"L");
		echo "</td>\n";
		echo "<td>";
		EnterpriseUserTeamspaces($user_id,$ui->WorkspaceID(),$ui->TeamspaceID(),"C");
		echo "</td>\n";
		echo "<td>";
		EnterpriseUserTeamspaces($user_id,$ui->WorkspaceID(),$ui->TeamspaceID(),"R");
		echo "</td>\n";
	echo "</tr>\n";
echo "</table>\n";
?>