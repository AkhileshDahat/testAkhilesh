<?php

define( '_VALID_MVH', 1 );
require_once "../../config.php";

/* DATABASE CONFIGURATION */
require_once "../../db_config.php";

/* OTHER CLASSES AND COMMON INCLUDES */
require_once "../../common_config.php";

$str = "
<table width=100% border=1>
	<tr>
		<td>ARTIST: IVAN LAM</td>
		<td>\"Ivan Lam: After all these years...\" will feature at Wei-Ling Gallery from August 16th 2009- September 12th 2009.</td>
		<td><img src=admin/modules/auction/images/3/ivan_lam.jpg>
	</tr>
	<tr>
		<td colspan=3>Ivan Lam's painting entitled, Three Buses received a final bid of HKD120,000 three times the higher estimate from Christie's Hong Kong Southeast Asian Modern and Contemporary Art May 2008 auction. The aforesaid as stated by the artist, \"forms one half of a diptych. This one is the first painting of two. When combined, the two paintings will show 3 buses, I will only paint the second painting sometime in mid 2009\".</td>
	</tr>
</table>";

$sql = "update auction_item_master set about = 
	'".mysql_real_escape_string($str)."'
	";
$db->Query($sql);
?>