<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/auction/classes/auction_item_id.php";

class BidID {

	function __construct() {
		$this->debug=false;
	}
	public function SetCredentials($auction_item_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($auction_item_id)) { $this->Errors("Invalid application"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->auction_item_id=$auction_item_id;

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function Bid($bid_amount) {


		if (!$this->parameter_check) { $this->Errors("Failed to bid [error 1]"); return false; }
		if (!IS_NUMERIC($bid_amount)) { $this->Errors("Invalid bid amount"); return False; }

		// TODO: Put in checks to see if the row exists

		// ITEM INCREMENTS
		$obj_item = new AuctionItemID;
		$obj_item->SetParameters($this->auction_item_id);
		$increments = $obj_item->GetInfo("bid_increments");

		// CURRENT BID
		$current_highest_bid = $this->GetItemHighestBid();
		//echo $current_highest_bid;
		// MIN BID AMOUNT
		$min_bid_amount = $current_highest_bid + $increments;
		//echo $min_bid_amount;

		if ($bid_amount < $min_bid_amount) { $this->Errors("Your bid is too low, please bid at least $min_bid_amount"); return False; }

		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."auction_bid_master
					(user_id, auction_id, auction_item_id, bid_amount, datetime_bid)
					VALUES (
					'".$_SESSION['user_id']."',
					'',
					'".$this->auction_item_id."',
					'".$bid_amount."',
					sysdate()
					)
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			return False;
		}
	}

	public function QBid() {


		if (!$this->parameter_check) { $this->Errors("Failed to bid [error 1]"); return false; }
		//if (!IS_NUMERIC($bid_amount)) { $this->Errors("Invalid bid amount"); return False; }

		// TODO: Put in checks to see if the row exists

		// ITEM INCREMENTS
		$obj_item = new AuctionItemID;
		$obj_item->SetParameters($this->auction_item_id);
		$increments = $obj_item->GetInfo("bid_increments");

		// CURRENT BID
		$current_highest_bid = $this->GetItemHighestBid();
		//echo $current_highest_bid;
		// MIN BID AMOUNT
		$min_bid_amount = $current_highest_bid + $increments;
		//echo $min_bid_amount;

		//if ($bid_amount < $min_bid_amount) { $this->Errors("Your bid is too low, please bid at least $min_bid_amount"); return False; }

		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."auction_bid_master
					(user_id, auction_id, auction_item_id, bid_amount, datetime_bid)
					VALUES (
					'".$_SESSION['user_id']."',
					'',
					'".$this->auction_item_id."',
					'".$min_bid_amount."',
					sysdate()
					)
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			return False;
		}
	}


	public function GetItemHighestBid() {

		if (!$this->parameter_check) { $this->Errors("Failed to bid [error 1]"); return false; }

		$db=$GLOBALS['db'];
		$c = "";
		$sql="SELECT max(bid_amount) as max
					FROM ".$GLOBALS['database_prefix']."auction_bid_master abm
					WHERE abm.auction_item_id = '".$this->auction_item_id."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				return $row['max'];
			}
		}
		else {
			return 0;
		}
	}

	public function GetUserHighestBid($user_id) {

		if (!$this->parameter_check) { $this->Errors("Failed to bid [error 1]"); return false; }

		$db=$GLOBALS['db'];
		$c = "";
		$sql="SELECT max(bid_amount) as max
					FROM ".$GLOBALS['database_prefix']."auction_bid_master abm
					WHERE abm.auction_item_id = '".$this->auction_item_id."'
					AND abm.user_id = '".mysql_real_escape_string($user_id)."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				return $row['max'];
			}
		}
		else {
			return 0;
		}
	}

	public function Debug($desc) {
		if ($this->debug==True) {
			echo $desc."<br>\n";
		}
	}

	private function Errors($err) {
		$this->errors.=$err."<br />";
	}

	public function ShowErrors() {
		return $this->errors;
	}
}
?>