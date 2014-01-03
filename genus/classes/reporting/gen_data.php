<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class GenData {

	public function __construct() {

	}

	public function GenMonthLongYearData($sql) {
		$arr_months=array("January","February","March","April","May","June","July","August","September","October","November","December");
		$arr_totals=array();
		$db=$GLOBALS['db'];
		$result = $db->Query($sql);
		$i=0;
		if ($db->NumRows($result) > 0) {
			//$this->i=0;
			while ($row = $db->FetchArray($result)) {
				if ($row['total'] > 0) {
					$total_chk=$row['total'];
				}
				else {
					$total_chk=0;
				}
				for ($i=0;$i<count($arr_months);$i++) {
					if ($row['month_name']==$arr_months[$i]) {
						$arr_totals[$i]=$total_chk;
					}
					else {
						if (!ISSET($arr_totals[$i])) {
							$arr_totals[$i]=0;
						}
					}
				}
				$i++;
			}
		}
		else {
			return False;
		}
		return $arr_totals;
	}

	public function GenArrayData($sql) {
		$arr_totals=array();
		$arr_desc=array();
		$db=$GLOBALS['db'];
		$result = $db->Query($sql);
		$i=0;
		if ($db->NumRows($result) > 0) {
			while ($row = $db->FetchArray($result)) {
				$arr_totals[$i]=$row['total'];
				$arr_desc[$i]=$row['description'];
				$i++;
			}
		}
		$this->arr_totals=$arr_totals;
		$this->arr_desc=$arr_desc;
	}

	public function GenPiePercentages() {
		$total=0;
		for ($i=0;$i<count($this->arr_totals);$i++) {
			$total+=$this->arr_totals[$i];
		}

		for ($i=0;$i<count($this->arr_totals);$i++) {
			$this->arr_totals[$i]=($this->arr_totals[$i]/$total)*100;
		}
	}

	public function GetVar($var) {
		if (ISSET($this->$var)) {
			return $this->$var;
		}
		else {
			return "False";
		}
	}
}
?>