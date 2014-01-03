<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class AuctionID {

	function __construct() {
		$this->debug=false;
	}
	public function SetParameters($auction_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($auction_id)) { $this->Errors("Invalid application"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->auction_id=$auction_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info();

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."auction_master am
					WHERE am.auction_id = '".$this->auction_id."'
					";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				/* HERE WE CALL THE FIELDS AND SET THEM INTO DYNAMIC VARIABLES */
				$arr_cols=$this->db->GetColumns($result);
				for ($i=1;$i<count($arr_cols);$i++) {
					$col_name=$arr_cols[$i];
					$this->$col_name=$row[$col_name];
				}
			}
		}
	}

	public function GetInfo($v) {
		if (ISSET($this->$v)) {
			return $this->$v;
		}
		else {
			return "";
		}
	}

	public function SetVariable($variable,$variable_val) {
		$this->$variable=EscapeData($variable_val);
	}

	public function ShowAuctions() {
		$db=$GLOBALS['db'];
		$c = "";
		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."auction_master am
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			$c .= "<table class=plain2>\n";
				$c .= "<tr class=colhead>\n";
					$c .= "<td>Auction Name</td>\n";
					$c .= "<td>Start Date</td>\n";
					$c .= "<td>End Date</td>\n";
					$c .= "<td>View</td>\n";
				$c .= "</tr>\n";
			while($row = $db->FetchArray($result)) {
				$c .= "<tr>\n";
					$c .= "<td>".$row['auction_name']."</td>\n";
					$c .= "<td>".$row['datetime_start']."</td>\n";
					$c .= "<td>".$row['datetime_end']."</td>\n";
					$c .= "<td><a href='index.php?module=auction&task=auction_items&auction_id=".$row['auction_id']."'><img src='admin/modules/auction/images/buttons/go.gif' border=0></a></td>\n";
				$c .= "</tr>\n";
			}
			$c .= "</table>";
		}
		else {
			$c .= "<div class=empty>No items to show</div>\n";
		}

		return $c;
	}

	public function ShowAuctionItems($auction_id) {
		$db=$GLOBALS['db'];
		$c = "";
		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."auction_item_master am
					WHERE am.auction_id = '".$auction_id."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			$c .= "<table class=plain2>\n";
				$c .= "<tr class=colhead>\n";
					$c .= "<td>Item Name</td>\n";
					$c .= "<td>Reserve Amount</td>\n";
					$c .= "<td>Bid Increments</td>\n";
					$c .= "<td>View</td>\n";
				$c .= "</tr>\n";
			while($row = $db->FetchArray($result)) {
				$c .= "<tr>\n";
					$c .= "<td>".$row['item_name']."</td>\n";
					$c .= "<td align=right>".$row['reserve_amount']."</td>\n";
					$c .= "<td align=right>".$row['bid_increments']."</td>\n";
					$c .= "<td align=center><a href='index.php?module=auction&task=auction_item&auction_item_id=".$row['auction_item_id']."'><img src='admin/modules/auction/images/buttons/go.gif' border=0></a></td>\n";
				$c .= "</tr>\n";
			}
			$c .= "</table>";
		}
		else {
			$c .= "<div class=empty>No items to show</div>\n";
		}

		return $c;
	}

	public function ShowAuctionItem($auction_item_id) {
		$db=$GLOBALS['db'];
		$c = "";
		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."auction_item_master am
					WHERE am.auction_item_id = '".$auction_item_id."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			$c .= $this->ShowAuctionItemImages($auction_item_id);
			$c .= "<table border=0 class=plain3>\n";
			while($row = $db->FetchArray($result)) {
				$c .= "<tr>\n";
					$c .= "<td>\n";
					$c .= $row['about'];
					if (strlen($row['about1']) > 0) {
						$c .= "<a href='index.php?module=auction&task=auction_item&auction_item_id=$auction_item_id&read_more=y'> Read More</a><br />\n";
						if (ISSET($_GET['read_more'])) {
							$c .= "<span id='about1'>".$row['about1']."</span>\n";
						}
					}
					$c .= "</td>\n";
					$c .= "</tr>\n";
			}
			$c .= "</table>";
		}
		else {
			$c .= "<div class=empty>No items to show</div>\n";
		}

		return $c;
	}

	public function ShowAuctionItemImages($auction_item_id) {
		$db=$GLOBALS['db'];
		$c = "";
		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."auction_item_images aii
					WHERE aii.auction_item_id = '".$auction_item_id."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			$c .= "<table border=0>\n";
			while($row = $db->FetchArray($result)) {
				$c .= "<tr>\n";
					$c .= "<td><a href='index.php?module=auction&task=auction_item&auction_item_id=".$row['auction_item_id']."'><img src='admin/modules/auction/images/$auction_item_id/".$row['image_filename']."' border=0></a></td>\n";
				$c .= "</tr>\n";
			}
			$c .= "</table>";
		}
		else {
			$c .= "<div id=empty>No items to show</div>\n";
		}

		return $c;
	}

	public function ShowAuctionHighestBid($auction_item_id) {

		require_once $GLOBALS['dr']."modules/auction/classes/bid_id.php";
		$c = "";

		$obj = new BidID;
		$obj->SetCredentials($auction_item_id);
		$highest = $obj->GetItemHighestBid();
		$user_highest = $obj->GetUserHighestBid($_SESSION['user_id']);

		if ($user_highest > 0) {
			$c .= "<div class=highlight>Your highest bid is: ".number_format($user_highest)."</div>\n";
		}
		else {
			$c .= "<div id=empty>No current bid</div>\n";
		}
		if ($highest > 0) {

			$c .= "<div class=highlight>Current highest bid is: ".number_format($highest)."</div>\n";
		}
		else {
			$c .= "<div id=empty>No current bid</div>\n";
		}
		$obj_item = new AuctionItemID;
		$obj_item->SetParameters($auction_item_id);
		// INCREMENTS
		$increments = $obj_item->GetInfo("bid_increments");
		// CURRENT BID
		$current_highest_bid = $obj->GetItemHighestBid();
		if ($current_highest_bid == 0) {
			$current_highest_bid = $obj_item->GetInfo("reserve_amount");
		}
		// ADD TWO UP
		$min_bid_amount = $current_highest_bid + $increments;
		$this->min_bid_amount = $min_bid_amount;
		$c .= "<div class=highlight>Next bid must be: ".number_format($min_bid_amount)."</div>";

		return $c;
	}

	public function Debug($desc) {
		if ($this->debug==True) {
			echo $desc."<br>\n";
		}
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}
}
?>