<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class Setup {

	function Setup() {

		$db=$GLOBALS['db'];

		$sql="SELECT allow_signup, allow_forgot_password,
				signup_max_teamspaces,signup_max_size,signup_max_users,signup_enterprise,signup_expiry_days,signup_workspace_logo
				FROM ".$GLOBALS['database_prefix']."core_setup
				WHERE id = '1'
				";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$this->allow_signup=$row["allow_signup"];
				$this->allow_forgot_password=$row["allow_forgot_password"];
				/* SIGNUP VARIABLES */
				$this->signup_max_teamspaces=$row["signup_max_teamspaces"];
				$this->signup_max_size=$row["signup_max_size"];
				$this->signup_max_users=$row["signup_max_users"];
				$this->signup_enterprise=$row["signup_enterprise"];
				$this->signup_expiry_days=$row["signup_expiry_days"];
				$this->signup_workspace_logo=$row["signup_workspace_logo"];

			}
		}
	}
	function AllowSignup() {	return $this->allow_signup; }
	function AllowForgotPassword() {	return $this->allow_forgot_password; }
	/* SIGNUP VARIABLES */
	function SignupMaxTeamspaces() {	return $this->signup_max_teamspaces; }
	function SignupMaxSize() {	return $this->signup_max_size; }
	function SignupMaxUsers() {	return $this->signup_max_users; }
	function SignupEnterprise() {	return $this->signup_enterprise; }
	function SignupExpiryDays() {	return $this->signup_expiry_days; }
	function SignupWorkspaceLogo() {	return $this->signup_workspace_logo; }

}
?>