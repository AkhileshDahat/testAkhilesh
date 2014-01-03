<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";

function BidForm($auction_item_id) {

	$c="";

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=auction&dtask=bid&auction_item_id=$auction_item_id","post","document_settings","");
	$form->Header("admin/images/crystalclear/48x48/apps/access.png","BID HERE");

	$form->Input("Enter Bid: ","bid_amount");

	$obj_item = new AuctionItemID;
	$obj_item->SetParameters($auction_item_id);
	$increments = $obj_item->GetInfo("bid_increments");

	$form->InformationCell("Note: You can only bid higher in increments of $increments");

	//$form->Submit("Save","FormSubmit");
	$c.=$form->CloseForm();
	$c.=$form->DrawForm();
	//$c.=$form->SetFocus("bid_amount");

	return $c;
}

function BidFormBlocks($auction_item_id,$min_amt) {

	$c="";

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetParam("btn_save","Place Bid");
	$form->SetCredentials("index.php?module=auction&task=auction_item&dtask=bid&auction_item_id=$auction_item_id","post","document_settings","1");
	$form->Header("admin/images/crystalclear/48x48/apps/access.png","BID HERE");

	//$form->Input("Quick Bid: ","bid_amt","","","",$min_amt,5);
	$form->DescriptionCell("Place Quick Bid","<a href=index.php?module=auction&task=auction_item&auction_item_id=$auction_item_id&dtask=qbid><img src=admin/modules/auction/images/buttons/bid.gif></a>");
	$form->InformationCell("Note: This will place a bid of ".number_format($min_amt));
	$form->BreakCell("OR");
	$form->InputBlocks("Enter Bid: ",array("num_1","num_2","num_3"),",000");

	$obj_item = new AuctionItemID;
	$obj_item->SetParameters($auction_item_id);
	$increments = $obj_item->GetInfo("bid_increments");

	$form->InformationCell("Note: You can only bid higher in increments of ".number_format($increments)	);

	//$form->Submit("Save","FormSubmit");
	$c.=$form->CloseForm();
	$c.=$form->DrawForm();
	//$c.=$form->SetFocus("bid_amount");

	return $c;
}
?>