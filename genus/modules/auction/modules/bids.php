<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function LoadTask() {

	$c="";

	$_SESSION['auction_bid_master1']="yes";

	$c .= "<iframe src='modules/auction/bin/auction_bid_master.php' width='700' height='600' frameborder='0' border='0' style='border:0px solid #000000' />";

	return $c;
}
?>