<?php
$arr = array("dz_agent_counter.php","dz_agent_detail.php","dz_bill_category.php","dz_bill_payee.php","dz_customer.php");

foreach ($arr as $a) {
	echo "<a href=$a>$a</a> | ";
}
?>