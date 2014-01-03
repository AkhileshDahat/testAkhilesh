<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class AuctionItemID {

	function __construct() {
		$this->debug=false;
	}
	public function SetParameters($auction_item_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($auction_item_id)) { $this->Errors("Invalid item"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->auction_item_id=$auction_item_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info();

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."auction_item_master am
					WHERE am.auction_item_id = '".$this->auction_item_id."'
					";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				/* HERE WE CALL THE FIELDS AND SET THEM INTO DYNAMIC VARIABLES */
				$arr_cols=$this->db->GetColumns($result);
				for ($i=1;$i<count($arr_cols);$i++) {
					$col_name=$arr_cols[$i];
					$this->$col_name=$row[$col_name];
				}
			}
		}
	}

	public function GetInfo($v) {
		if (ISSET($this->$v)) {
			return $this->$v;
		}
		else {
			return "";
		}
	}

	public function Debug($desc) {
		if ($this->debug==True) {
			echo $desc."<br>\n";
		}
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}
}
?>