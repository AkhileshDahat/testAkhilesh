<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."include/functions/misc/yes_no_image.php");

class UsersPendingApproval {

	public function __construct() {
		$this->c="";
	}

	public function Filter() {
		$c="<form method='get' action='index.php'>\n";
		$c.="<input type='hidden' name='module' value='hrms'>\n";
		$c.="<input type='hidden' name='task' value='user_list'>\n";
		if (ISSET($_GET['search_full_name'])) { $search_full_name=EscapeData($_GET['search_full_name']); } else { $search_full_name=""; }
		$c.="Search: <input type='text' name='search_full_name' value='".$search_full_name."'>\n";
		$c.="</form>\n";
		return $c;
	}

	public function SQLFilter() {
		if (ISSET($_GET['search_full_name'])) {
			return "AND um.full_name LIKE '%".EscapeData($_GET['search_full_name'])."%'";
		}
	}

	public function ShowUsers() {
		$db=$GLOBALS['db'];

		$sql="SELECT um.user_id, um.full_name, um.login
					FROM ".$GLOBALS['database_prefix']."core_space_users csu, ".$GLOBALS['database_prefix']."core_user_master um
					WHERE csu.workspace_id = ".$GLOBALS['workspace_id']."
					AND csu.teamspace_id ".$GLOBALS['teamspace_sql']."
					AND csu.approved = 'n'
					AND csu.user_id = um.user_id
					".$this->SQLFilter()."
					ORDER BY um.full_name
					";

		//echo $sql."<br>";
		$result = $db->Query($sql);
		$this->c.="<table class='plain' border='0'>\n";
			$this->c.="<tr>\n";
				$this->c.="<td class='bold' colspan='2'>Users Pending Approval</td>\n";
				$this->c.="<td class='bold' colspan='2'>".$this->Filter()."</td>\n";
			$this->c.="</tr>\n";
			$this->c.="<tr class='colhead'>\n";
				$this->c.="<td>Full Name</td>\n";
				$this->c.="<td>Email</td>\n";
				$this->c.="<td>Delete</td>\n";
				$this->c.="<td>Approve</td>\n";
			$this->c.="</tr>\n";
			if ($db->NumRows($result) > 0) {
				while($row = $db->FetchArray($result)) {
					$this->c.="<tr>\n";
						$this->c.="<td>".$row['full_name']."</td>\n";
						$this->c.="<td>".$row['login']."</td>\n";
						$this->c.="<td><a href='index.php?module=hrms&task=users_pending_approval&subtask=delete&user_id=".$row['user_id']."'>Delete</a></td>\n";
						$this->c.="<td><a href='index.php?module=hrms&task=users_pending_approval&subtask=approve&user_id=".$row['user_id']."'>Approve</a></td>\n";
					$this->c.="</tr>\n";
				}
			}
		$this->c.="</table>\n";
		return $this->c;
	}
}
?>