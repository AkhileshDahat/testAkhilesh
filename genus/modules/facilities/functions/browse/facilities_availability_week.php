<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."include/functions/forms/auto_redirect.php");
require_once($GLOBALS['dr']."include/functions/date_time/day_of_week.php");
require_once $GLOBALS['dr']."classes/form/create_form.php";

require_once($GLOBALS['dr']."modules/facilities/classes/facility_master.php");

function FacilitiesAvailability($facility_id="",$date="") {
	$ui=$GLOBALS['ui'];

	/* THIS IS THE REDIRECT */
	//$c=AutoRedirectDropdown("facility_id","facility_name","facility_master","facility_id",$facility_id,"index.php?module=facilities&task=availability&facility_id=","","WHERE workspace_id=".$ui->WorkspaceID()."	AND teamspace_id ".$GLOBALS['teamspace_sql']);
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=facilities&task=availability","post","facilities_availability");
	$form->Header("crystalclear/48x48/apps/access.png","Query Availability");
	$form->ShowDropDown("Choose Facility","facility_id","facility_name","facility_master","facility_id",$facility_id,"","WHERE workspace_id=".$GLOBALS['ui']->WorkspaceID()." AND teamspace_id ".$GLOBALS['teamspace_sql']."","");
	$form->Date("Date","date",$date);
	$form->Submit("Filter Now","FormSubmit");
	$form->CloseForm();
	return $form->DrawForm();

	/* THIS IS THE AVAILABILITY CHART */
	if (IS_NUMERIC($facility_id)) {
		$fm=new FacilityMaster;
		$fm->Info($facility_id);
		$c.="<table border='1' class='plain'>\n";

		/* CHECK FOR BOOKING INTERVAL GREATER THAN 60 MINUTES */
		//if ($fm->GetInfo("booking_interval") == 120) { $i_plus=2; } elseif ($fm->GetInfo("booking_interval")==240) { $i_start=6; } elseif ($fm->GetInfo("booking_interval")==1440) { $i_start=1; } else { $i_start=24; $i_plus=2; }
		$i_plus=($fm->GetInfo("booking_interval")/60);
		/* START LOOPING */
		for ($i=0;$i<24;$i) {
			for ($j=0;$j<60;$j) {
				for ($k=1;$k<=7;$k++) {
					if ($i==0 && $j==0 && $k==1) {
						$c.="<tr>\n";
						for ($l=1;$l<=7;$l++) {
							$c.="<td>".DayOfWeek($l,True)."</td>\n";
						}
						$c.="</tr><tr>\n";
					}
					if ($i==0) { $i_show="00"; } else { if ($i<10) { $i_show="0".$i; } else { $i_show=$i; } }
					if ($j==0) { $j_show="00"; } else { $j_show=$j; }
					$friendly_val=$i_show.":".$j_show;
					if ($k==1) { $c.="<tr>\n"; }
						$c.="<td class='alternatecell2' onMouseOver=\"this.className='alternatecell1'\" onMouseOut=\"this.className='alternatecell2'\">".$friendly_val." - </td>\n";
					if ($k==7) { $c.="</tr>\n"; }
				}
				$j+=$fm->GetInfo("booking_interval");
			}
			$i+=$i_plus;
		}
		$c.="</table>\n";

	}
	return $c;
}

?>