<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Rico SimpleGrid-Example 1</title>

<script src="../../src/prototype.js" type="text/javascript"></script>
<script src="../../src/rico.js" type="text/javascript"></script>
<link href="../client/css/demo.css" type="text/css" rel="stylesheet" />

<?
require "../../plugins/php/SimpleGrid.php";
?>

<script type='text/javascript'>
Rico.loadModule('SimpleGrid','greenHdg.css');

Rico.onLoad( function() {
  var opts = {  
    columnSpecs   : ['specQty']  // display first column as a numeric quantity
  };
  var ex1=new Rico.SimpleGrid ('ex1', opts);
});
</script>

</head>

<body>

<div id='explanation'>
This grid was created using the SimpleGrid plug-in!
Compare it to ex1simple.aspx - which is a LiveGrid.
</div>

<?
$numcol=15;
$grid=new SimpleGrid();

$grid->AddHeadingRow(true);
for ($c=1; $c<=$numcol; $c++) {
  $grid->AddCell("Column $c");
}

for ($r=1; $r<=100; $r++) {
  $grid->AddDataRow();
  $grid->AddCell($r);
  for ($c=2; $c<=$numcol; $c++) {
    $grid->AddCell("Cell $r:$c");
  }
}
$grid->Render("ex1", 1);   // output html
?>

</body>
</html>

