<?php
/* DEFINE A VALUE TO RESTRICT DIRECT FILE ACCESS */
define('_VALID_INTRANET_', 1 );

class GetData {

	function ExecuteTempSQL($sql) {
		$db=$GLOBALS['db'];
		$result = $db->query($sql);
	}

	function GetData($sql,$sql1="") {

		if (!EMPTY($sql1)) {
			$this->ExecuteTempSQL($sql1);
		}

		$db=$GLOBALS['db'];
		$this->max_total=0;

		//$app_db=$GLOBALS['app_db'];
		$result = $db->query($sql);

		if ($db->NumRows($result) > 0) {
			$this->i=0;
			while ($row = $db->FetchArray($result)) {
				if ($row['total'] > 0) {
					$total_chk=$row['total'];
				}
				else {
					$total_chk=0;
				}

				/* CHECK FOR THE MAX VALUE */
				if ($row['total'] > $this->max_total) {
					$this->max_total=$row['total'];
				}

				$this->AddSingleValue($total_chk, $row['legend']);
				$this->total_count+=$total_chk;
			}
			//$this->total=$total;
			//$this->legend=$legend;

			//$this->total_noarray=substr($total_noarray, 0, -1);
			//$this->legend_noarray=substr($legend_noarray, 0, -1);
			//print_r ($this->total_noarray).'<br>';
		}
		else {
			return False;
		}
	}

	function AddSingleValue($add_total, $add_legend) {

		$this->array_total[]=$add_total;
		$this->array_legend[]=$add_legend;
	}

	function GetTotal() {
		return $this->array_total;
	}
	function GetLegend() {
		return $this->array_legend;
	}

	function GetTotalNoArray() {
		//return $this->total_noarray;
		$j=0;
		//return array;
	}
	function GetLegendNoArray() {return $this->legend_noarray; }

	function CountTotal() {
		return $this->total_count;
	}

	function GetMaxTotal() {
		return $this->max_total;
	}
}
?>