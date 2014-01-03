<?php
/* THIS ENSURES WE ARE ABLE TO CONTROL OUR INCLUDE FILES */
define( '_VALID_MVH', 1 );

require_once "../../../../config.php";
include $dr."include/jgraph/src/jpgraph.php";
include $dr."include/jgraph/src/jpgraph_bar.php";

require_once $dr."classes/graphs/get_data.php";

if (IS_NUMERIC($_GET['width']) && IS_NUMERIC($_GET['height'])) {
	$width=$_GET['width'];
	$height=$_GET['height'];
}
else {
	$width=500;
	$height=500;
}

YearlyExpense($width,$height);

function YearlyExpense($width,$height) {

	$graph_title="Yearly Expenses";

	$sql="SELECT YEAR(b.purchase_date) as legend,
				SUM(b.cost_price) as total
				FROM ".$GLOBALS['database_prefix']."batch b
				GROUP BY YEAR(b.purchase_date)
				ORDER BY YEAR(b.purchase_date) DESC
				";
	$gd=new GetData($sql);

	$datay=$gd->GetTotal();
	$datax=$gd->GetLegend();
	//print_r ($datax);
	$graph = new Graph($width,$height,'auto');
	$graph->SetScale("textlin");
	$graph->SetFrame(false);
	$graph->Set90AndMargin(50,20,20,20);
	$graph->SetMarginColor('white');
	$graph->SetBox();
	$graph->SetBackgroundGradient('#996699','lightblue',GRAD_HOR,BGRAD_PLOT);
	$graph->title->Set($graph_title);
	$graph->title->SetFont(FF_VERDANA,FS_BOLD,10);
	//$graph->subtitle->Set("(Non optimized)");

	$graph->xaxis->SetTickLabels($datax);
	$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,7);
	$graph->xaxis->SetLabelMargin(5);
	$graph->xaxis->SetLabelAlign('right');

	$graph->yaxis->scale->SetGrace(20);
	$graph->yaxis->Hide();
	$bplot = new BarPlot($datay);
	$bplot->SetShadow();
	$bplot->SetWidth(0.5);
	$bplot->SetFillGradient('#336699','#FFFFFF',GRAD_HOR);
	$bplot->value->Show();
	$bplot->value->SetFont(FF_ARIAL,FS_BOLD,7);
	//$bplot->value->SetAlign('right');
	$bplot->value->SetColor("black");
	$bplot->value->SetFormat('%.0f');
	//$bplot->SetValuePos('center');

	$graph->Add($bplot);

	//$txt = new Text('Note: Higher value is better.');
	//$txt->SetPos(190,399,'center','bottom');
	//$txt->SetFont(FF_COMIC,FS_NORMAL,8);
	//$graph->Add($txt);

	$graph->Stroke();
}

?>