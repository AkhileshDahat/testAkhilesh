<?
if (!isset ($_SESSION)) session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: ".gmdate("D, d M Y H:i:s",time()+(-1*60))." GMT");
header('Content-type: text/html; charset=utf-8');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Menu</title>
<script src="../../src/prototype.js" type="text/javascript"></script>
<script src="../../src/rico.js" type="text/javascript"></script>
<link href="../client/css/demo.css" type="text/css" rel="stylesheet" />

<?
$sqltext=".";  // force filtering to "on" in settings box
require "applib.php";
require "../../plugins/php/ricoLiveGridForms.php";
require "settings.php";
?>

<script type='text/javascript'>
Rico.loadModule('LiveGridForms','Calendar','Tree');
<?
setStyle();
?>

// Results of Rico.loadModule may not be immediately available!
// In which case, "new Rico.CalendarControl" would fail if executed immediately.
// Therefore, wrap it in a function.
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

<?
include "menu.php";
//************************************************************************************************************
//  LiveGrid Plus-Edit Example
//************************************************************************************************************
if (OpenGridForm("", "customer_master")) {
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

  $GLOBALS['oForm']->options["borderWidth"]=0;
  GridSettingsTE($GLOBALS['oForm']);

  //$GLOBALS['oForm']->options["DebugFlag"]=true;
  //$GLOBALS['oDB']->debug=false;
  DefineFields();
  //echo "<p><textarea id='orders_debugmsgs' rows='5' cols='80' style='font-size:smaller;'></textarea>";

}

function DefineFields() {
  global $oForm,$oDB;


  //$oForm->AddPanel("Basic Info");

  $oForm->AddEntryField("customer_id", "ID", "B", "<auto>");
  $oForm->CurrentField["width"]=50;

  $oForm->AddEntryField("full_name", "Name", "T", "");
  $oForm->CurrentField["width"]=180;

  $oForm->AddEntryField("id_number", "ID#", "T", "");
  $oForm->CurrentField["width"]=120;

  $oForm->AddEntryField("date_of_birth", "DOB", "T", "");
  $oForm->CurrentField["width"]=90;
  //$oForm->CurrentField["InsertOnly"]=true;

  $oForm->AddEntryField("gender", "Gender", "T", "");
  $oForm->CurrentField["width"]=70;

  $oForm->AddEntryField("address1", "Address1", "T", "");
  $oForm->CurrentField["width"]=150;

  $oForm->AddEntryField("address2", "Address2", "T", "");
  $oForm->CurrentField["width"]=150;

  $oForm->AddEntryField("address3", "Address3", "T", "");
  $oForm->CurrentField["width"]=150;

  $oForm->AddEntryField("postcode", "Postcode", "T", "");
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryField("city", "City", "T", "");
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryField("state", "State", "T", "");
  $oForm->CurrentField["width"]=100;

  $oForm->AddEntryFieldW("country", "Country", "N", "",100);
  $oForm->CurrentField["width"]=100;

  $oForm->DisplayPage();
}
?>
</body>
</html>
