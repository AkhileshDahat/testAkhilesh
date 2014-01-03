<?php
class details {
	public function __construct() {

	}

	public function Menu() {
		return array("Summary","History");
	}

	public function MenuClicked() {
		$c = "";

		if ($_GET['plugin_menu'] == "Summary") {
			GLOBAL $ti;

			$c="<table border='0' cellpadding='0' cellspacing='0' width='100%' class='plain'>\n";
				$c.="<tr class='colhead'>\n";
					$c.="<td colspan='2'>Ticket Details</td>\n";
				$c.="</tr>\n";
				$c.=$this->ShowRow("Ticket ID","ticket_id");
				$c.=$this->ShowRow("Title","title");
				$c.=$this->ShowRow("Logged by","user_id_logging_name");
				$c.=$this->ShowRow("Submitted","date_submit");
				$c.=$this->ShowBreak();
				$c.=$this->ShowRow("Description","description");
				$c.=$this->ShowRow("Technical Description","technical_description");
				$c.=$this->ShowBreak();
				$c.=$this->ShowRow("Solution","solution");
				$c.=$this->ShowRow("Technical Solution","technical_solution");
				$c.=$this->ShowBreak();
				$c.=$this->ShowRow("Due Date","date_due");
				$c.=$this->ShowRow("Start work","date_start_work");
				$c.=$this->ShowRow("Estimated Completion","date_estimated_completion");
				$c.=$this->ShowRow("Actual Completion","date_complete");
			$c.="</table>\n";

		}
		elseif ($_GET['plugin_menu'] == "History") {
			GLOBAL $ti;

			$sql="SELECT hth.description,hth.date_logged,um.full_name
									FROM ".$GLOBALS['database_prefix']."helpdesk_ticket_history hth, ".$GLOBALS['database_prefix']."core_user_master um
									WHERE hth.ticket_id = '".$ti->GetColVal("ticket_id")."'
									AND hth.user_id = um.user_id
									ORDER BY hth.history_id DESC
									";
			//echo $sql;
			$sql = str_replace("\n","",$sql);
			//$sql = str_replace("  ","",$sql);
			$sql = str_replace("\t","",$sql);
			//$sql = str_replace(Chr(13),"",$sql);
			//echo $sql;
			$_SESSION['ex2'] = $sql;

			$head = $GLOBALS['head'];
			$head->IncludeFile("rico2rc2");

			$c="";

			$c.="<p class='ricoBookmark'><span id='ex2_timer' class='ricoSessionTimer'></span><span id='ex2_bookmark'>&nbsp;</span></p>\n";

			$c.="<table id='ex2' class='ricoLiveGrid' cellspacing='0' cellpadding='0'>\n";
				$c.="<colgroup>\n";
					$c.="<col style='width:250px;' >\n";
					$c.="<col style='width:140px;' >\n";
					$c.="<col style='width:140px;' >\n";
				$c.="</colgroup>\n";
			  $c.="<tr>\n";
				  $c.="<th>Description</th>\n";
				  $c.="<th>Date</th>\n";
				  $c.="<th>User</th>\n";
			  $c.="</tr>\n";
			$c.="</table>\n";
		}
		return $c;
	}

	public function NewTicket() {

	}

	public function EditTicket() {

	}

	public function DeleteTicket() {

	}

	private function ShowRow($desc,$val) {
		$c="<tr class='alternatecell2' onMouseOver=\"this.className='alternateover'\" onMouseOut=\"this.className='alternatecell2'\">\n";
			$c.="<td class='bold' valign='top'>".$desc."</td>\n";
			$c.="<td>".$GLOBALS['ti']->GetColVal($val)."</td>\n";
		$c.="</tr>\n";
		return $c;
	}

	private	function ShowYesNoRow($di,$desc,$val) {
		$c="<tr>\n";
			$c.="<td class='bold'>".$desc."</td>\n";
			$c.="<td>".YesNoImage($GLOBALS['ti']->GetColVal($val))."</td>\n";
		$c.="</tr>\n";
		return $c;
	}

	private function ShowBreak() {
		$c="<tr>\n";
			$c.="<td colspan='2'><hr></td>\n";
		$c.="</tr>\n";
		return $c;
	}

	public function __destruct() {

	}
}
?>