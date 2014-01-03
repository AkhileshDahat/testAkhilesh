<?php
class twitter {
	public function __construct() {

	}

	public function Menu() {
		//return array("Twitter");
	}

	public function MenuClicked() {

	}

	public function NewTicket() {
		//echo "Doing Twitter bit <br />";
		//echo "ID: ".$GLOBALS['ticket_id']."<br />";
		//echo "Title: ".$GLOBALS['ti']->GetColVal("title")."<br />";
		//echo "Desc: ".$GLOBALS['ti']->GetColVal("description")."<br />";

		// Set username and password
		$username = 'oldfoot';
		$password = 'melissa';
		// The message you want to send
		$message = "has submitted a new genus helpdesk problem - ".$GLOBALS['ti']->GetColVal("title");
		// The twitter API address
		$url = 'http://twitter.com/statuses/update.xml';
		// Alternative JSON version
		// $url = 'http://twitter.com/statuses/update.json';
		// Set up and execute the curl process
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, "$url");
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_handle, CURLOPT_POST, 1);
		curl_setopt($curl_handle, CURLOPT_POSTFIELDS, "status=$message");
		curl_setopt($curl_handle, CURLOPT_USERPWD, "$username:$password");
		$buffer = curl_exec($curl_handle);
		curl_close($curl_handle);
		// check for success or failure
		if (empty($buffer)) {
		    return 'TWITTER - Failed to add ticket details';
		} else {
		    return 'TWITTER - Ticket posted to twitter';
		}

	}

	public function EditTicket() {

		// Set username and password
		$username = 'oldfoot';
		$password = 'melissa';
		// The message you want to send
		$message = "has edited a genus helpdesk problem - Ticket ID: ".$GLOBALS['ticket_id'];
		// The twitter API address
		$url = 'http://twitter.com/statuses/update.xml';
		// Alternative JSON version
		// $url = 'http://twitter.com/statuses/update.json';
		// Set up and execute the curl process
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, "$url");
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_handle, CURLOPT_POST, 1);
		curl_setopt($curl_handle, CURLOPT_POSTFIELDS, "status=$message");
		curl_setopt($curl_handle, CURLOPT_USERPWD, "$username:$password");
		$buffer = curl_exec($curl_handle);
		curl_close($curl_handle);
		// check for success or failure
		if (empty($buffer)) {
		    return 'TWITTER - Failed to edit ticket details';
		} else {
		    return 'TWITTER - Ticket [edited] posted to twitter';
		}

	}

	public function DeleteTicket() {

	}

	public function __destruct() {

	}
}
?>