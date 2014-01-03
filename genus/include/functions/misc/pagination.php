<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function Pagination($total_record,$limit_record,$offset,$pageLink) {
	/*
	echo $total_record."<br>";
	echo $limit_record."<br>";
	echo $offset."<br>";
	echo $pageLink."<br>";
	*/
	if ($total_record>$limit_record) {
		//$pagination.="[ ";
		if ($offset!=0) {
			$nos=$offset-$limit_record;
			//$pagination.="<a href='$pageLink&offset=$nos'><img src='img/themes/default/previous.gif' border='0'></a> ";
			$pagination.="<a href='$pageLink&offset=$nos'><< Previous</a> ";
		}
		//else {$pagination.="<font class='nlinkl'><img src='img/themes/".$GLOBALS['it']->GetTitle()."/buttons/previous_small.gif' border='0'></font>";}

		for ($i=0;$i<$total_record/$limit_record;$i++){
			$tpg=$total_record/$limit_record-round($total_record/$limit_record);
			if ($tpg>0){$tpg=(round($total_record/$limit_record)+1);}
			else if ($tpg<0){$tpg=round($total_record/$limit_record);}
			else if ($tpg==0){$tpg=round($total_record/$limit_record);}

			$upCap=4;
			$loCap=5;
			if ($offset/$limit_record==0){$upCap=0;$loCap=9;}
			if ($offset/$limit_record==1){$upCap=1;$loCap=8;}
			if ($offset/$limit_record==2){$upCap=2;$loCap=7;}
			if ($offset/$limit_record==3){$upCap=3;$loCap=6;}
			if ($offset/$limit_record==4){$upCap=4;$loCap=5;}
			if ($offset/$limit_record+1==$tpg-0){$upCap=9;$loCap=0;}
			if ($offset/$limit_record+1==$tpg-1){$upCap=8;$loCap=1;}
			if ($offset/$limit_record+1==$tpg-2){$upCap=7;$loCap=2;}
			if ($offset/$limit_record+1==$tpg-3){$upCap=6;$loCap=3;}
			if ($offset/$limit_record+1==$tpg-4){$upCap=5;$loCap=4;}

			if ($i>=($offset/$limit_record)-$upCap&&$i<=($offset/$limit_record)+$loCap){
				$page_number=$i+1;
				$new_offset=$i*$limit_record;

				if (strcmp(($offset/$limit_record),$i)!=0) {

					$pagination.=" <a class='font20b' href='$pageLink&offset=$new_offset'><u>".$page_number."</u></a> ";
				}
				else {
					$pagination.=" <font class='font20b'>$page_number</font> ";}
			}
		}

		if ($total_record-($offset+$limit_record) > 0) {
			$nos=$offset+$limit_record;
			//$pagination.=" <a href='$pageLink&offset=$nos'><img src='img/themes/default/next.gif' border='0'></a> ";
			$pagination.="<a href='$pageLink&offset=$nos'>Next >></a> ";
		}
		//else {$pagination.="<font class='nlinkl'><img src='img/themes/".$GLOBALS['it']->GetTitle()."/buttons/next_small.gif' border='0'></font>";}
		//$pagination.=" ]";
	}
	return $pagination;
}
?>