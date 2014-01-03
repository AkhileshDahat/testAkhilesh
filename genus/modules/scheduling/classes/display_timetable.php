<?php
/* SAMPLE USAGE
$obj_st=new DisplayTimetable;
$obj_st->SetVars();

$obj_st->InitTableArray();
echo $obj_st->DrawInitTableArray();

function DrawButton($p_day,$p_time) {
	return "<input type='button' name='block_button' value='Block' onClick=\"DoSomething('','".$p_day."-".$p_time."')\">\n";
}

*/


class DisplayTimetable {

	public function SetVariable($variable,$variable_val) {
		if (!is_array($variable_val)) {
			$this->$variable=EscapeData($variable_val);
		}
		else {
			$this->$variable=$variable_val;
		}

	}

	public function InitTableArray() {

		$this->total_intervals=($this->end_time-$this->start_time)/$this->interval;

		$this->arr_init_table=array();
		for ($i=0;$i<=count($this->arr_days);$i++) {
			for ($j=0;$j<$this->total_intervals;$j++) {
				if ($i>0 && $j>0) {
					$this->arr_init_table[$i][$j]="a";
				}
				else {
					$this->arr_init_table[$i][$j]="h";
				}

			}
		}
		$this->arr_init_table[0][0]="";
		/* SET THE TABLE ARRAY FOR COLUMN 0 TO THE DAYS OF THE WEEK */
		$this->InitTableLeftHeader();
		/* SET THE TABLE ARRAY FOR ROW 0 TO THE INTERVALS */
		$this->InitTableTopHeader();
	}

	public function DrawInitTableArray() {
		$c="<table border=1>\n";
		for ($i=0;$i<=count($this->arr_days);$i++) {
			$c.="<tr>";
			for ($j=0;$j<$this->total_intervals;$j++) {
				//$c.="<td>".$this->arr_init_table[$i][$j]."</td>";
				$c.="<td>";
				if ($i==0 || $j==0) {
					$c.=$this->arr_init_table[$i][$j];
				}
				else {
					$callback_function=$this->callback_function;
					$c.=$callback_function($this->arr_init_table[$i][0],$this->arr_init_table[0][$j],$this->NextSlotAddInterval($this->arr_init_table[0][$j]));
				}

				$c.="</td>";
			}
			$c.="</tr>";
		}
		$c.="</table>\n";

		return $c;

	}

	private function InitTableLeftHeader() {

		for ($i=0;$i<count($this->arr_days);$i++) {

				$this->arr_init_table[$i+1][0]=$this->arr_days[$i];

		}
	}

	private function InitTableTopHeader() {
		//for ($i=$this->start_time;$i<=$this->end_time;$i+$this->interval) {
		$temp_interval=$this->start_time;
		for ($i=0;$i<$this->total_intervals;$i++) {
			if ($i>0) {
				$temp_display_interval=($temp_interval+=$this->interval);
				$display_interval = mktime(0, $temp_display_interval, 0, date("m")  , date("d"), date("Y"));

				$this->arr_init_table[0][$i]=date("G:i",$display_interval);
			}
		}
	}

	private function NextSlotAddInterval($p_slot) {

		list($hr, $mn) = split(':', $p_slot);
		$seconds=($hr*60)+($mn);
		$next_interval=$seconds+$this->interval;

		$date_seconds=mktime(0, $next_interval, 0, date("m")  , date("d"), date("Y"));
		return date("G:i",$date_seconds);
	}
}
?>