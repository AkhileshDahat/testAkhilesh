<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/design/right_click.php");

class ShowResults {

	function __construct() {
		$this->display_data=""; /* INITIALISE THE VARIABLE */
		$this->extra_cell[0]=""; /* THE EXTRA CELL FIRST ARRAY MUST BE EMPTY */
		$this->limit=10;
		$this->link_data=""; /* USED TO KEEP TRACK OF FOOTER LINKS */
	}

	/* SET SOME PARAMETERS */
	public function SetParameters($show_row_break=False,$show_header=False) {
		$this->show_row_break=$show_row_break;
	}

	public function TableTitle($header_img,$header_desc) {
		if (!EMPTY($header_img)) {
			$head_img="<img src='".$GLOBALS['wb']."images/".$header_img."'>";
		}
		else {
			$head_img="";
		}
		
		$this->display_table_title.="<tr height='1' class='recordsethead'><td colspan='".count($this->friendly_cols)."' height='1'>".$head_img.$header_desc."</td></tr>\n";
		
	}

	public function TableFilter($content,$form="",$close_form="") {
		$this->display_table_title.=$form."<tr class='colfoot'><td colspan='".count($this->friendly_cols)."'>".$content."</td></tr>".$close_form."\n";
	}

	/* QUERY THE DATABASE AND PUT THE RESULTS INTO A 2D ARRAY */
	public function Query($sql) {

		/* GLOBAL DATABASE OBJECT */
		$db=$GLOBALS['db'];

		/* EXECUTE THE ENTIRE QUERY WITHOUT LIMITING THE RECORDS */
		$full_result=$db->Query($sql);

		/* COUNT THE NUMBER OF ROWS FOUND */
		$this->num_rows_found=$db->NumRows($full_result);

		/* STORE THE COLUMN NAMES */
		$this->get_col_names=$db->GetColumns($full_result);

		/* DO SOME FILTERING - NOT IMPLEMENTED */
		if (ISSET($_GET['col_sort'])) {
			//$sql.="ORDER BY ".$this->get_col_names[$_GET['col_sort']];
		}

		/* DETERMINE THE OFFSET */
		if (ISSET($_GET['offset']) && IS_NUMERIC($_GET['offset']) && $_GET['offset'] < $this->num_rows_found) {
			$this->offset=$_GET['offset'];
			$this->start_offset=0;
			$this->sql_start_offset=$_GET['offset'];
		}
		else {
			$this->start_offset=10;
			$this->offset=10;
			$this->sql_start_offset=0;
		}

		/* APPEND A LIMIT TO THE QUERY */
		$sql.=" LIMIT ".$this->sql_start_offset.",".$this->limit;
		//echo $sql;
		$result=$db->Query($sql);

		$this->count_rows=0;

		$result = $db->Query($sql);
		//$this->cols=pg_fetch_all_columns($result);

		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				for ($i=0;$i<count($this->cols);$i++) {
					$this->temp_col_data[$this->count_rows][$i]=$row[$this->cols[$i]];
				}
				$this->count_rows++;
			}
		}
		else {
			$this->display_data.="<tr>\n";
				$this->display_data.="<td colspan='".count($this->friendly_cols)."'><div id='nodata'>No data exists</div></td>\n";
			$this->display_data.="</tr>\n";
		}
	}

	/* COLUMN SORTING */
	public function ColumnSorting() {
	}

	/* CUSTOMISE THE COLUMN HEADS*/
	public function DrawFriendlyColHead($cols) {
		$this->friendly_cols=$cols;
		$this->display_data.="<tr class='colhead'>\n";
		for ($i=0;$i<count($cols);$i++) {
			//$this->display_data.="<td><a href='".$_SERVER['SCRIPT_NAME']."?".$_SERVER['QUERY_STRING']."&col_sort=".$this->cols[$i]."'><font color='white'>".$cols[$i]."</font></a></td>\n";
			$this->display_data.="<td>".$cols[$i]."</td>\n";
		}
		$this->display_data.="</tr>\n";
	}

	/* STORE THE COLUMNS IN AN ARRAY SOMEHOW THE PG_FETCH_ALL_COLUMNS ISNT WORKING */
	public function Columns($cols) {
		$this->cols=$cols;
	}

	/* COUNT ALL ROWS */
	public function CountRows() {
		return $this->count_rows;
	}

	/* THIS OVERRIDES A COLUMN FOR EXAMPLE YES NO / IMAGES / BUTTONS ETC */
	public function ColDefault($col_number,$type) {
		for ($i=0;$i<$this->count_rows;$i++) {
			if ($type=="yesno") {
				if ($this->temp_col_data[$i][$col_number]=="n") { $this->temp_col_data[$i][$col_number]="No"; } else { $this->temp_col_data[$i][$col_number]="Yes"; }
			}
			elseif ($type=="yesnoimage") {
				if ($this->temp_col_data[$i][$col_number]=="n") { $this->temp_col_data[$i][$col_number]="<img src='images/nuvola/16x16/actions/button_cancel.png' border='0'>"; } else { $this->temp_col_data[$i][$col_number]="<img src='images/nuvola/16x16/actions/button_accept.png' border='0'>"; }
			}
		}
	}

	/* THIS MODIFIES ONE OF THE VARIABLES */
	public function ModifyData($row_number,$col_number,$new_val) {
		$this->temp_col_data[$row_number][$col_number]=$new_val;
	}

	/* GET A COLUMN NAME FROM THE ARRAY */
	function GetColVar($col_no) {
		return $this->cols[$col_no];
	}

	/* GET A VALUE OF A ROW */
	public function GetRowVal($row_number,$col_number) {
		return $this->temp_col_data[$row_number][$col_number];
	}

	/* WRAP THE DATA IN OUR TABLE */
	public function WrapData() {
		$class="alternatecell1";
		for ($i=0;$i<$this->count_rows;$i++) {
			/* DO SOME CLASS MANIPULATION */
			if ($class=="alternatecell1") { $class="alternatecell2"; } else { $class="alternatecell1"; }
			$this->display_data.="<tr class='".$class."' onMouseOver=\"this.className='alternateover'\" onMouseOut=\"this.className='".$class."'\">\n";
			/* GET THE ARRAY AND PUT IT INTO A CELL */
			for ($j=0;$j<count($this->cols);$j++) {
				$this->display_data.="<td>".$this->temp_col_data[$i][$j]."</td>\n";
			}
			/* ADD ANY EXTRA COLUMNS */
			for ($k=0;$k<count($this->extra_cell);$k++) {
				$this->display_data.="<td>".$this->extra_cell[$k]."</td>\n";
			}
			$this->display_data.="</tr>\n";
			/* THIS ADDS A LINE BREAK BETWEEN EACH RECORD */
			if ($this->show_row_break) {
				$this->display_data.="<tr height='1'><td colspan='".count($this->friendly_cols)."' bgcolor='#dedede' height='1'></td></tr>\n";
			}
		}
	}

	/* METHOD TO ADD NEW CELLS TO EACH ROW */
	public function AddCell($v) {
		//echo count($this->extra_cell);
		$this->extra_cell[]=$v;
	}

	public function RemoveColumn($col_number) {
		for ($i=0;$i<$this->count_rows;$i++) {
			$this->temp_col_data[$i][$col_number]="";
		}
	}

	public function Footer() {
		/* REMOVE THE OFFSET AND LIMIT FROM THE QUERYSTRING OR IT KEEPS GETTING LONGER AND LONGER */
		//echo "&offset=".($this->offset-$this->start_offset)."&limit=".$this->limit;
		//echo $this->num_rows_found;
		$query_string_filter=STR_REPLACE("&offset=".($this->offset-$this->start_offset)."&limit=".$this->limit,"",$_SERVER['QUERY_STRING']);

		$this->display_data.="<tr class='colhead'>\n";
			$this->display_data.="<td colspan='".count($this->friendly_cols)."'>\n";
			$this->display_data.="<table class='plain'>\n";
				$this->display_data.="<tr>\n";
					//echo $this->offset."<br>";
					//echo $this->num_rows_found."<br>";
					//echo $this->limit."<br>";
					//echo ($this->num_rows_found-$this->limit)."<br>";
					//if ($this->offset < ($this->num_rows_found-$this->limit)) {
					if ($this->offset < $this->num_rows_found) {
						$this->display_data.="<td><a href='".$_SERVER['SCRIPT_NAME']."?".$query_string_filter."&offset=".($this->offset-$this->start_offset+$this->limit)."&limit=".$this->limit."'>Next 10</a></td>\n";
					}
					if (($this->count_rows+$this->offset-$this->start_offset) > $this->limit) {
						$this->display_data.="<td><a href='".$_SERVER['SCRIPT_NAME']."?".$query_string_filter."&offset=".($this->offset-$this->limit)."&limit=".$this->limit."'>Previous 10</a></td>\n";
					}
					$this->display_data.="<td>Showing record:".($this->offset-$this->start_offset+1)." to ".($this->offset-$this->start_offset+$this->count_rows)."</td>\n";
					$this->display_data.="<td>Records found:".$this->num_rows_found."</td>\n";
				$this->display_data.="</tr>\n";
			$this->display_data.="</table>\n";
			$this->display_data.="<br></td>\n";
		$this->display_data.="</tr>\n";
	}

	public function FooterLinks($link,$desc) {
		$this->link_data.="| <a href='".$link."'>".$desc."</a> | \n";
	}

	public function CompileFooterLinks() {
		$this->display_data.="<tr>\n";
			$this->display_data.="<td colspan='".count($this->friendly_cols)."'>\n";
			$this->display_data.=$this->link_data;
			$this->display_data.="</td>\n";
		$this->display_data.="</tr>\n";
	}

	/* DRAW THE TABLE */
	public function Draw($width="100%") {
		$table="<table width='".$width."' class='plain' border='0' bordercolor='#BFBFBF' cellspacing='0' cellpading='0'>\n";
		$table.=$this->display_table_title;
		$table.=$this->display_data;
		$table.="</table>\n";
		return $table;
	}

	function __destruct() {
		$this->display_data="";
		$this->display_table_title="";
	}
}
?>