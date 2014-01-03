<?php
//require_once $GLOBALS['dr'].'include/functions/forms/dynamic_select.php';
//require_once $GLOBALS['dr'].'/include/functions/forms/dynamic_dropdown.php';
//require_once $GLOBALS['dr'].'/include/functions/date_time/date_select.php';
require_once $GLOBALS['dr']."classes/form/create_form.php";

function TicketsMonthlyTotals() {

	$c="";

	$c.="<script language='JavaScript'>\n";
	$c.="function AdvancedFilter()\n";
	$c.="{\n";
	$c.="document.getElementById('srch_filter').className='showBlk';\n";
	$c.="document.getElementById('adv_filter').className='hideBlk';\n";
	$c.="document.getElementById('no_filter').className='showBlk';\n";
	$c.="}\n";
	$c.="function NoFilter()\n";
	$c.="{\n";
	$c.="document.getElementById('adv_filter').className='showBlk';\n";
	$c.="document.getElementById('no_filter').className='hideBlk';\n";
	$c.="document.getElementById('srch_filter').className='hideBlk';\n";
	$c.="}\n";
	$c.="</script>\n";

	$c.="<div class='showBlk' id='adv_filter'>\n";
	$c.="<a href='#stayhere' onClick='AdvancedFilter()'><img src='images/nuovext/22x22/actions/gear.png' border='0'> Advanced Filter</a><br>\n";
	$c.="</div>\n";
	$c.="<div class='hideBlk' id='no_filter'>\n";
	$c.="<a href='#stayhere' onClick='NoFilter()'><img src='images/nuovext/22x22/actions/gear.png' border='0'> No Filter</a><br>\n";
	$c.="</div>\n";

	$c.="<div class='hideBlk' id='srch_filter'>\n";

		/* DESIGN THE FORM */
		$form=new CreateForm;
		$form->SetCredentials("index.php","get","create_ticket","");
		$form->Header("crystalclear/48x48/apps/access.png","Monthly cases");

		$form->Hidden("module","helpdesk");
		$form->Hidden("task","reports");
		$form->Hidden("report","tickets_monthly_totals");
		//$form->Hidden("stype","tickets_monthly_totals");

		if (ISSET($_GET['significance_id']) && IS_NUMERIC($_GET['significance_id'])) { $significance_id=$_GET['significance_id']; } else { $significance_id=""; }
		$form->ShowDropDown("Significance","significance_id","significance_name","helpdesk_significance_master","significance_id",$significance_id,"","WHERE workspace_id = '".$GLOBALS['workspace_id']."' AND teamspace_id ".$GLOBALS['teamspace_sql']);

		if (ISSET($_GET['yr']) && IS_NUMERIC($_GET['yr'])) { $yr=$_GET['yr']; } else { $yr=""; }
		$form->DateYearDropDown("Date From:",$yr,"yr");
		$c.=$form->DrawForm();

	$c.="</div>\n";


	$c.="<img src='modules/helpdesk/bin/reports/tickets_monthly_totals.php?significance_id=".$significance_id."&yr=".$yr."' border='0'></a>";

	return $c;
}
?>