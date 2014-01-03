<?php
error_reporting(0);
if (!isset ($_SESSION)) session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: ".gmdate("D, d M Y H:i:s",time()+(-1*60))." GMT");
header('Content-type: text/html; charset=utf-8');

define( '_VALID_MVH', 1 );
require "../../../../config.php";
require $GLOBALS['dr']."db_config.php";
require $GLOBALS['dr']."common_config.php";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Rico LiveGrid-Example 2 (editable)</title>
<script src="../../src/min.rico.js" type="text/javascript"></script>
<link href="../../src/css/min.rico.css" type="text/css" rel="stylesheet" />
<link href="../client/css/demo.css" type="text/css" rel="stylesheet" />

<?php
$sqltext=".";  // force filtering to "on" in settings box
require "../../../../include/rico21/examples/php/applib.php";
require "../../../../include/rico21/plugins/php/ricoLiveGridForms.php";
require "../../../../include/rico21/examples/php/settings.php";
?>

<script type='text/javascript'>
<?php
setStyle();
?>

// ricoLiveGridForms will call orders_FormInit right before grid & form initialization.

function orders_FormInit() {
  var cal=new Rico.CalendarControl("Cal");
  RicoEditControls.register(cal, Rico.imgDir+'calarrow.png');
  cal.addHoliday(25,12,0,'Christmas','#F55','white');
  cal.addHoliday(4,7,0,'Independence Day-US','#88F','white');
  cal.addHoliday(1,1,0,'New Years','#2F2','white');

  var CustTree=new Rico.TreeControl("CustomerTree","CustTree.php");
  RicoEditControls.register(CustTree, Rico.imgDir+'dotbutton.gif');
}
</script>
<style type="text/css">
div.ricoLG_outerDiv thead .ricoLG_cell, div.ricoLG_outerDiv thead td, div.ricoLG_outerDiv thead th {
	height:2.5em;
}
.ricoLG_bottom div.ricoLG_cell {
  white-space:nowrap;
}
</style>
</head>
<body>

<?php
//************************************************************************************************************
//  LiveGrid Plus-Edit Example
//************************************************************************************************************
//  Matt Brown
//************************************************************************************************************
if (OpenGridForm("", "delivery_master")) {
  if ($oForm->action == "table") {
    DisplayTable();
  }
  else {
    DefineFields();
  }
} else {
  echo 'open failed';
}
CloseApp();

function DisplayTable() {
  echo "<table id='explanation' border='0' cellpadding='0' cellspacing='5' style='clear:both'><tr valign='top'><td>";
  GridSettingsForm();
  echo "</td><td>This example demonstrates how database records can be updated via AJAX. ";
  echo "Try selecting add, edit, or delete from the pop-up menu. ";
  echo "If you select add, then click the '...' button next to customer, you will see the Rico tree control.";
  echo "The actual database updates have been disabled for security reasons and result in an error.";
  echo "</td></tr></table>";
  $GLOBALS['oForm']->options["borderWidth"]=0;
  GridSettingsTE($GLOBALS['oForm']);
  $GLOBALS['oForm']->options["Filter"]=true;

  $GLOBALS['oForm']->TableFilter="agent_id=3";
  //$GLOBALS['oDB']->debug=true;
  DefineFields();
  //echo "<p><textarea id='orders_debugmsgs' rows='5' cols='80' style='font-size:smaller;'></textarea>";
}

function DefineFields() {
  global $oForm,$oDB;

  $oForm->AddPanel("Core");

  $oForm->AddEntryField("delivery_id", "ID", "B", "<auto>");
  $oForm->CurrentField["width"]=50;

  //$oForm->AddEntryField("agent_id", "Agent", "H", "");
  //$oForm->AddEntryField("agent_id", "Agent", "H", $_SESSION['user_id']);

	/*
		PART 1
	*/
	$oForm->AddPanel("Payer");
  $oForm->AddEntryField("payer_charge_type", "Payment Charge Type", "T", "");
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryField("payment_type", "Payment Type", "T", "");
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryField("insured", "Insured", "T", "");
  $oForm->CurrentField["width"]=50;

  $oForm->AddEntryField("insured_amount", "Insured Amt", "F", "");
  $oForm->CurrentField["format"]="DOLLAR";
  $oForm->CurrentField["width"]=50;

	/*
		PART 2 - LINK THE REMAINING ITEMS FROM THE CUSTOMER TABLE
	*/
	$oForm->AddPanel("From");
  $oForm->AddEntryField("customer_id", "Customer ID", "SL", "");
  $oForm->CurrentField["SelectSql"]="SELECT customer_id, full_name FROM customer_master";
  $oForm->CurrentField["width"]=100;

	/*
		PART 3
	*/
	$oForm->AddPanel("To");
  $oForm->AddEntryField("receiver_company_name", "Receiver Name", "T", "");
  $oForm->CurrentField["width"]=50;

  $oForm->AddEntryField("receiver_address", "Receiver Address", "T", "");
  $oForm->CurrentField["width"]=50;

  $oForm->AddEntryField("postcode_id", "Postcode", "SL", "");
  $oForm->CurrentField["SelectSql"]="SELECT postcode_id, postcode FROM postcode_master";
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryField("receiver_country", "Country", "T", "");
  $oForm->CurrentField["width"]=50;

  $oForm->AddEntryField("receiver_contact_name", "Name", "T", "");
  $oForm->CurrentField["width"]=50;

  $oForm->AddEntryField("receiver_contact_phone", "Phone", "T", "");
  $oForm->CurrentField["width"]=50;

  $oForm->AddEntryField("receiver_contact_fax", "Fax", "T", "");
  $oForm->CurrentField["width"]=50;

  $oForm->AddEntryField("receiver_contact_email", "Email", "T", "");
  $oForm->CurrentField["width"]=50;

	/*
		PART 4
	*/
	$oForm->AddPanel("Shipping");
	$oForm->AddEntryField("total_packages", "Total Packages", "T", "");
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryField("total_weight", "Total Weight", "T", "");
  $oForm->CurrentField["width"]=100;

	/*
		PART 5
	*/
	$oForm->AddPanel("Contents");
  $oForm->AddEntryField("contents_description", "Contents", "T", "");
  $oForm->CurrentField["width"]=100;

	/*
		PART 6
	*/
	$oForm->AddPanel("Customs");
  $oForm->AddEntryField("shipper_vat_gst_number", "Shipper VAT GST", "T", "");
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryField("receiver_vat_gst_number", "Receiver VAT GST", "T", "");
  $oForm->CurrentField["width"]=100;

	$oForm->AddEntryField("customs_declared_value", "Declared customs value", "T", "");
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryField("commodity_code", "Commodity Code", "T", "");
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryField("export_type", "Export Type", "T", "");
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryField("destination_duties", "Dest Duties", "F", "");
  $oForm->CurrentField["format"]="DOLLAR";
  $oForm->CurrentField["width"]=100;

	/*
		PART 8
	*/
	$oForm->AddPanel("Services");
  $oForm->AddEntryField("service_type", "Service Type", "T", "");
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryField("instructions", "Instructions", "T", "");
  $oForm->CurrentField["width"]=100;


  $oForm->AddEntryField("charges_services", "Services", "F", "");
  $oForm->CurrentField["format"]="DOLLAR";
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryField("charges_other", "Other $", "F", "");
  $oForm->CurrentField["format"]="DOLLAR";
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryField("charges_insurance", "Insurange $", "F", "");
  $oForm->CurrentField["format"]="DOLLAR";
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryField("charges_vat", "VAT $", "F", "");
  $oForm->CurrentField["format"]="DOLLAR";
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryField("transport_collection_sticker_no", "Transport Collection Sticker No", "T", "");
  $oForm->CurrentField["width"]=100;

	$oForm->AddEntryField("payment_no", "Payment Number", "T", "");
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryField("payment_details_type", "Payment Type", "T", "");
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryField("payment_expiry", "Payment Expiry", "D", "");
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryField("picked_up_by", "Pickup By", "T", "");
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryField("pickup_route_number", "Route Number", "T", "");
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryField("pickup_datetime", "Pickup Date", "DT", "");
  $oForm->CurrentField["width"]=100;

  $oForm->DisplayPage();
}
?>


</body>
</html>
